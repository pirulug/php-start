<?php

function upload_file($file, $uploadDir, $options = []) {
  $defaultOptions = [
    'allowedTypes' => [
      'pdf',
      'docx',
      'xlsx',
      'txt'
    ],
    'maxSize'      => 5 * 1024 * 1024, // 5MB
    'fileName'     => null // Nombre generado automáticamente
  ];
  $options        = array_merge($defaultOptions, $options);

  // Verificar si se subió el archivo
  if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
    return ['success' => false, 'message' => 'Error al subir el archivo.'];
  }

  // Validar tipo de archivo (basado en extensión)
  $originalExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if (!in_array($originalExtension, $options['allowedTypes'])) {
    return ['success' => false, 'message' => "La extensión .$originalExtension no está permitida. Extensiones permitidas: " . implode(", ", $options['allowedTypes']) . "."];
  }

  // Validar tamaño del archivo
  if ($file['size'] > $options['maxSize']) {
    return ['success' => false, 'message' => "El archivo excede el tamaño máximo permitido de " . ($options['maxSize'] / 1024 / 1024) . " MB."];
  }

  // Crear el directorio de subida si no existe
  if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
      return ['success' => false, 'message' => 'No se pudo crear el directorio de destino.'];
    }
  }

  // Determinar el nombre del archivo
  $fileName = $options['fileName']
    ? $options['fileName'] . '.' . $originalExtension
    : uniqid('file_', true) . '.' . $originalExtension;
  $filePath = rtrim($uploadDir, '/') . '/' . $fileName;

  // Mover el archivo al directorio de destino
  if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    return ['success' => false, 'message' => 'Error al mover el archivo al directorio de destino.'];
  }

  return ['success' => true, "message" => "Archivo subido con éxito.", 'file_name' => $fileName, 'file_path' => $filePath];
}
