<?php

// -----------------------------------------------------------------------------
// SECCIÓN: CONTROLADOR DE LISTADO DE LOGS (ADMIN) - Solo contadores para tabs
// -----------------------------------------------------------------------------

$logs_dir = BASE_DIR . "/storage/logs";

/**
 * Escanea de forma recursiva buscando archivos .log.
 *
 * @param string $dir Directorio base a escanear.
 * @param string $relative_prefix Prefijo de ruta relativa acumulado.
 * @return array Listado de archivos de logs encontrados.
 */
function scan_logs_directory($dir, $relative_prefix = "") {
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
      $files = array_merge($files, scan_logs_directory($full_path, $relative_path));
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

$all_logs = scan_logs_directory($logs_dir);

// Contar grupos unicos por tab para los badges
$count_users  = 0;
$count_ips    = 0;
$count_otros  = 0;
$users_seen   = [];
$ips_seen     = [];

foreach ($all_logs as $log_file) {
  $parts = explode("/", $log_file["relative_path"]);

  if (count($parts) >= 2 && $parts[0] === "usuarios") {
    $users_seen[$parts[1]] = true;
  } elseif (count($parts) >= 2 && $parts[0] === "ips") {
    $ips_seen[$parts[1]] = true;
  } else {
    $count_otros++;
  }
}

$count_users = count($users_seen);
$count_ips   = count($ips_seen);
$total_files = count($all_logs);
