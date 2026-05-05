<?php

$module = $args['module'] ?? '';
$file   = $args['file'] ?? '';

if (!$module || !$file) {
  http_response_code(400);
  exit('Invalid request');
}

$path = BASE_DIR . "/app/admin/modules/{$module}/scripts/{$file}.script.js";

if (!file_exists($path)) {
  http_response_code(404);
  exit('Script not found');
}

header('Content-Type: application/javascript');
readfile($path);
exit;