<?php

// -----------------------------------------------------------------------------
// SECCIÓN: CONTROLADOR DE LISTADO DE LOGS (ADMIN)
// -----------------------------------------------------------------------------

$logs_dir = BASE_DIR . "/storage/logs";
$log_folders = [];

/**
 * Escanea de forma recursiva buscando archivos .log y devolviendo su informacion.
 *
 * @param string $dir Directorio base a escanear.
 * @param string $relative_prefix Prefijo de ruta relativa acumulado.
 * @return array Listado de archivos de logs encontrados.
 */
function scan_logs_directory(string $dir, string $relative_prefix = "") {
  $files = [];
  if (!is_dir($dir)) {
    return $files;
  }
  
  foreach (scandir($dir) as $item) {
    if ($item === '.' || $item === '..') {
      continue;
    }
    
    $full_path = $dir . '/' . $item;
    $relative_path = $relative_prefix ? $relative_prefix . '/' . $item : $item;
    
    if (is_dir($full_path)) {
      $files = array_merge($files, scan_logs_directory($full_path, $relative_path));
    } elseif (is_file($full_path) && str_ends_with($item, '.log')) {
      $files[] = [
        'relative_path' => $relative_path,
        'name'          => $item,
        'size'          => filesize($full_path),
        'date'          => filemtime($full_path)
      ];
    }
  }
  
  return $files;
}

$all_logs = scan_logs_directory($logs_dir);

$grouped_logs = [
  'usuarios' => [],
  'ips'      => [],
  'otros'    => []
];

foreach ($all_logs as $log_file) {
  $parts = explode('/', $log_file['relative_path']);
  
  if (count($parts) >= 2 && $parts[0] === 'usuarios') {
    $username = $parts[1];
    if (!isset($grouped_logs['usuarios'][$username])) {
      $grouped_logs['usuarios'][$username] = [];
    }
    $grouped_logs['usuarios'][$username][] = $log_file;
  } elseif (count($parts) >= 2 && $parts[0] === 'ips') {
    $ip = $parts[1];
    if (!isset($grouped_logs['ips'][$ip])) {
      $grouped_logs['ips'][$ip] = [];
    }
    $grouped_logs['ips'][$ip][] = $log_file;
  } else {
    $grouped_logs['otros'][] = $log_file;
  }
}

// Ordenar las fechas dentro de cada grupo para que los mas nuevos salgan primero
$sort_by_date_desc = function(&$files) {
  usort($files, function($a, $b) {
    return $b['date'] <=> $a['date'];
  });
};

foreach ($grouped_logs['usuarios'] as $username => &$files) {
  $sort_by_date_desc($files);
}
unset($files);

foreach ($grouped_logs['ips'] as $ip => &$files) {
  $sort_by_date_desc($files);
}
unset($files);

$sort_by_date_desc($grouped_logs['otros']);

// Ordenar nombres de usuario e IPs alfabeticamente para mejor localizacion visual
ksort($grouped_logs['usuarios']);
ksort($grouped_logs['ips']);

