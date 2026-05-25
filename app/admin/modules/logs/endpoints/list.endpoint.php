<?php

// -----------------------------------------------------------------------------
// SECCIÓN: ENDPOINT DE LISTADO DE LOGS PAGINADO (JSON)
// -----------------------------------------------------------------------------



$logs_dir   = BASE_DIR . "/storage/logs";
$tab        = $_GET["tab"]    ?? "usuarios";
$search     = strtolower(trim($_GET["search"] ?? ""));
$page       = max(1, (int)($_GET["page"] ?? 1));
$per_page   = 10;

if (!in_array($tab, ["usuarios", "ips", "otros"])) {
  echo json_encode(["error" => "Tab invalido"]);
  exit();
}

/**
 * Escanea de forma recursiva buscando archivos .log.
 *
 * @param string $dir Directorio base a escanear.
 * @param string $relative_prefix Prefijo de ruta relativa acumulado.
 * @return array Listado de archivos de logs encontrados.
 */
function scan_logs_ep($dir, $relative_prefix = "") {
  $files = [];
  if (!is_dir($dir)) {
    return $files;
  }

  foreach (scandir($dir) as $item) {
    if ($item === "." || $item === "..") {
      continue;
    }

    $full_path     = $dir . "/" . $item;
    $relative_path = $relative_prefix ? $relative_prefix . "/" . $item : $item;

    if (is_dir($full_path)) {
      $files = array_merge($files, scan_logs_ep($full_path, $relative_path));
    } elseif (is_file($full_path) && str_ends_with($item, ".log")) {
      $files[] = [
        "relative_path" => $relative_path,
        "name"          => $item,
        "size"          => filesize($full_path),
        "date"          => filemtime($full_path)
      ];
    }
  }

  return $files;
}

// -----------------------------------------------------------------------------
// SECCIÓN: AGRUPACION Y FILTRADO
// -----------------------------------------------------------------------------

$all_logs = scan_logs_ep($logs_dir);

$grouped = [
  "usuarios" => [],
  "ips"      => [],
  "otros"    => []
];

foreach ($all_logs as $log_file) {
  $parts = explode("/", $log_file["relative_path"]);

  if (count($parts) >= 2 && $parts[0] === "usuarios") {
    $key = $parts[1];
    if (!isset($grouped["usuarios"][$key])) {
      $grouped["usuarios"][$key] = [];
    }
    $grouped["usuarios"][$key][] = $log_file;
  } elseif (count($parts) >= 2 && $parts[0] === "ips") {
    $key = $parts[1];
    if (!isset($grouped["ips"][$key])) {
      $grouped["ips"][$key] = [];
    }
    $grouped["ips"][$key][] = $log_file;
  } else {
    $grouped["otros"][] = $log_file;
  }
}

// Ordenar archivos dentro de cada grupo por fecha desc
$sort_desc = function(&$files) {
  usort($files, function($a, $b) {
    return $b["date"] <=> $a["date"];
  });
};

foreach ($grouped["usuarios"] as $k => &$f) { $sort_desc($f); }
unset($f);
foreach ($grouped["ips"] as $k => &$f) { $sort_desc($f); }
unset($f);
$sort_desc($grouped["otros"]);

ksort($grouped["usuarios"]);
ksort($grouped["ips"]);

// -----------------------------------------------------------------------------
// SECCIÓN: PAGINACION POR TAB
// -----------------------------------------------------------------------------

$base_url = admin_route("log/view");

if ($tab === "otros") {
  // Para "otros" se paginan los archivos directamente
  $items = $grouped["otros"];

  // Filtrar por busqueda
  if ($search !== "") {
    $items = array_filter($items, function($file) use ($search) {
      return str_contains(strtolower($file["relative_path"]), $search)
          || str_contains(strtolower($file["name"]), $search);
    });
    $items = array_values($items);
  }

  $total   = count($items);
  $offset  = ($page - 1) * $per_page;
  $slice   = array_slice($items, $offset, $per_page);

  $rows = [];
  foreach ($slice as $file) {
    $rows[] = [
      "relative_path" => $file["relative_path"],
      "name"          => $file["name"],
      "size_kb"       => round($file["size"] / 1024, 2),
      "date_fmt"      => date("d/m/Y H:i:s", $file["date"]),
      "url"           => admin_route("log/view", [], ["f" => $file["relative_path"]])
    ];
  }

  echo json_encode([
    "tab"        => "otros",
    "page"       => $page,
    "per_page"   => $per_page,
    "total"      => $total,
    "has_more"   => ($offset + $per_page) < $total,
    "rows"       => $rows
  ]);
  exit();
}

// Para "usuarios" e "ips" se paginan los grupos (cada usuario/ip es un grupo)
$groups = $grouped[$tab];

// Filtrar grupos por busqueda (por nombre de grupo o por nombre de archivo dentro)
if ($search !== "") {
  $filtered = [];
  foreach ($groups as $group_key => $files) {
    $display_key = ($tab === "ips") ? str_replace("_", ":", $group_key) : $group_key;
    $group_match = str_contains(strtolower($display_key), $search);

    $matched_files = array_filter($files, function($file) use ($search) {
      return str_contains(strtolower($file["name"]), $search);
    });

    if ($group_match) {
      $filtered[$group_key] = $files;
    } elseif (!empty($matched_files)) {
      $filtered[$group_key] = array_values($matched_files);
    }
  }
  $groups = $filtered;
}

$group_keys = array_keys($groups);
$total      = count($group_keys);
$offset     = ($page - 1) * $per_page;
$page_keys  = array_slice($group_keys, $offset, $per_page);

$result_groups = [];
foreach ($page_keys as $gkey) {
  $display_key = ($tab === "ips") ? str_replace("_", ":", $gkey) : $gkey;
  $files_out   = [];

  foreach ($groups[$gkey] as $file) {
    $files_out[] = [
      "relative_path" => $file["relative_path"],
      "name"          => $file["name"],
      "size_kb"       => round($file["size"] / 1024, 2),
      "date_fmt"      => date("d/m/Y H:i:s", $file["date"]),
      "url"           => admin_route("log/view", [], ["f" => $file["relative_path"]])
    ];
  }

  $result_groups[] = [
    "key"         => $gkey,
    "display_key" => $display_key,
    "files"       => $files_out,
    "count"       => count($files_out)
  ];
}

echo json_encode([
  "tab"      => $tab,
  "page"     => $page,
  "per_page" => $per_page,
  "total"    => $total,
  "has_more" => ($offset + $per_page) < $total,
  "groups"   => $result_groups
]);
exit();
