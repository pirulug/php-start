<?php

// -----------------------------------------------------------------------------
// SECCIÓN: ENDPOINT DE SUBIDA DE ARCHIVOS DE KIKI
// -----------------------------------------------------------------------------

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// Obtener el token del Header de Autorización o del POST
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = '';

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
  $token = $matches[1];
} else {
  $token = $_POST['token'] ?? '';
}

if (empty($token)) {
  http_response_code(400);
  exit(json_encode([
    'success' => false,
    'message' => 'Token de autorizacion requerido.'
  ]));
}

// Descifrar y validar el token
try {
  $decrypted = $cipher->decrypt($token);

  if (empty($decrypted) || !str_contains($decrypted, ':')) {
    throw new Exception('Token invalido o corrupto.');
  }

  [$userId, $expiry, $filename] = explode(':', $decrypted, 3);

  if (time() > (int)$expiry) {
    throw new Exception('El token de subida ha expirado.');
  }
} catch (Exception $e) {
  http_response_code(401);
  exit(json_encode([
    'success' => false,
    'message' => 'No autorizado: ' . $e->getMessage()
  ]));
}

// Validar que se reciba el archivo en multipart/form-data
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
  http_response_code(400);
  exit(json_encode([
    'success' => false,
    'message' => 'Archivo no recibido o con errores en la subida.'
  ]));
}

// Validar extension y tipo de archivo
$fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if (!in_array($fileExtension, ['pdf', 'png', 'jpg', 'jpeg'])) {
  http_response_code(400);
  exit(json_encode([
    'success' => false,
    'message' => 'Tipo de archivo no permitido. Solo se aceptan PDF, PNG y JPG.'
  ]));
}

// Definir directorio y ruta final en storage
$uploadDir = BASE_DIR . "/storage/uploads/kiki";
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

// Asegurar nombre de archivo valido
$filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $filename);
$destPath = "{$uploadDir}/{$filename}";

// Guardar archivo
if (move_uploaded_file($_FILES['file']['tmp_name'], $destPath)) {
  $fileUrl = storage_uploads($filename, "kiki");

  http_response_code(200);
  exit(json_encode([
    'success' => true,
    'message' => 'Archivo subido correctamente por Kiki.',
    'filename' => $filename,
    'url' => $fileUrl
  ]));
} else {
  http_response_code(500);
  exit(json_encode([
    'success' => false,
    'message' => 'No se pudo guardar el archivo en el servidor.'
  ]));
}
