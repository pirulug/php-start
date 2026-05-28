<?php

// -----------------------------------------------------------------------------
// SECCIÓN: ENDPOINT DE SUBIDA DE IMÁGENES PARA OSAMU EDITOR
// -----------------------------------------------------------------------------

ini_set("display_errors", 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=utf-8");

// Validar que se reciba el archivo en multipart/form-data
if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
  http_response_code(400);
  exit(json_encode([
    "success" => false,
    "message" => "Archivo de imagen no recibido o con errores en la subida."
  ]));
}

// Sanitizar y validar la imagen con la función global del sistema
if (!clear_image($_FILES["image"])) {
  http_response_code(400);
  exit(json_encode([
    "success" => false,
    "message" => "El archivo no es una imagen válida o es potencialmente inseguro."
  ]));
}

// Directorio de subida temporal
$uploadDir = BASE_DIR . "/storage/uploads/temp";
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

// Generar nombre de archivo único
$fileExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
$uniqueName = "img_" . bin2hex(random_bytes(8)) . "_" . time() . "." . $fileExtension;
$destPath = "{$uploadDir}/{$uniqueName}";

// Mover el archivo subido
if (move_uploaded_file($_FILES["image"]["tmp_name"], $destPath)) {
  $fileUrl = storage_uploads($uniqueName, "temp");

  http_response_code(200);
  exit(json_encode([
    "success" => true,
    "message" => "Imagen subida correctamente.",
    "url" => $fileUrl
  ]));
} else {
  http_response_code(500);
  exit(json_encode([
    "success" => false,
    "message" => "No se pudo guardar la imagen en el servidor."
  ]));
}
