<?php

/**
 * Motor de carga automática para endpoints de módulos.
 */

$module = $args['module'] ?? '';
$file   = $args['file'] ?? '';

if (!$module || !$file) {
  http_response_code(400);
  exit(json_encode(['success' => false, 'message' => 'Solicitud inválida']));
}

$path = BASE_DIR . "/app/admin/modules/{$module}/endpoints/{$file}.endpoint.php";

if (!file_exists($path)) {
  http_response_code(404);
  exit(json_encode(['success' => false, 'message' => 'Endpoint no encontrado']));
}

// El core de index.php ya maneja las cabeceras JSON si es necesario, 
// pero aquí aseguramos el contexto.
header('Content-Type: application/json; charset=utf-8');

require_once $path;
exit;