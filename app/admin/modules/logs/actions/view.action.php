<?php

// -----------------------------------------------------------------------------
// SECCIÓN: CONTROLADOR DE VISUALIZACIÓN DE CONTENIDO DE UN LOG (ADMIN)
// -----------------------------------------------------------------------------

$file_param = $_GET['f'] ?? '';
$logs_base = BASE_DIR . "/storage/logs";

// Sanitizar y validar la ruta para evitar Directory Traversal (seguridad)
$real_base = realpath($logs_base);
$target_file = realpath($logs_base . '/' . $file_param);

if (!$target_file || !str_starts_with($target_file, $real_base) || !str_ends_with($target_file, '.log') || !is_file($target_file)) {
  $notifier->danger("Archivo de log no válido o no encontrado.")
    ->bootstrap()
    ->add();
  header("Location: " . admin_route("logs"));
  exit();
}

$raw_content = file_get_contents($target_file);
$lines = explode(PHP_EOL, $raw_content);
$parsed_entries = [];

// Parsear cada línea del log basándonos en la estructura: [fecha_hora] [NIVEL] [IP] [RUTA] Mensaje Contexto
foreach ($lines as $line) {
  $line = trim($line);
  if (empty($line)) {
    continue;
  }
  
  // Expresion regular para extraer la estructura por corchetes
  // Ej: [2026-05-25 04:04:14] [INFO] [127.0.0.1] [/panel/users] Mensaje {"context": 1}
  if (preg_match('/^\[(.*?)\]\s+\[(.*?)\]\s+\[(.*?)\]\s+\[(.*?)\]\s+(.*?)(?:\s+(\{.*?\}))?$/', $line, $matches)) {
    $parsed_entries[] = [
      'datetime' => $matches[1] ?? '',
      'level'    => $matches[2] ?? 'INFO',
      'ip'       => $matches[3] ?? '',
      'route'    => $matches[4] ?? '',
      'message'  => $matches[5] ?? '',
      'context'  => $matches[6] ?? ''
    ];
  } else {
    // Fallback para lineas de logs que no coincidan al 100% con la estructura estructurada
    $parsed_entries[] = [
      'datetime' => date('Y-m-d H:i:s', filemtime($target_file)),
      'level'    => 'RAW',
      'ip'       => '-',
      'route'    => '-',
      'message'  => $line,
      'context'  => ''
    ];
  }
}

// Invertir para mostrar las entradas más recientes al inicio
$parsed_entries = array_reverse($parsed_entries);
