<?php

/**
 * Motor de carga automática para endpoints del FRONT.
 */

$module = $args['module'] ?? '';
$file   = $args['file'] ?? '';

if (!$module || !$file) {
  http_response_code(400);
  exit(json_encode(['success' => false, 'message' => 'Solicitud inválida']));
}

$path = BASE_DIR . "/app/front/modules/{$module}/endpoints/{$file}.endpoint.php";

if (!file_exists($path)) {
  http_response_code(404);
  exit(json_encode(['success' => false, 'message' => 'Endpoint no encontrado']));
}

header('Content-Type: application/json; charset=utf-8');

require_once $path;
exit;