<?php


// function upload_image($file, $uploadDir, $imageSuported = ["jpg", "png", "jpeg"], $maxSize = 2 * 1024 * 1024, $extension = null, $optimize = 5, $fileName = null) {

// }

function upload_image($file, $uploadDir, $options = []) {
  // Configuración predeterminada
  $defaults = [
    "imageSupported" => ["jpg", "png", "jpeg"],  // Extensiones permitidas
    "maxSize"        => 2 * 1024 * 1024,         // Tamaño máximo: 2 MB
    "convertTo"      => null,                    // Convertir a: null, "jpg", "png", "webp"
    "optimize"       => 5,                       // Calidad (0 a 10)
    "fileName"       => null                     // Nombre del archivo personalizado
  ];
  // Combinar opciones personalizadas con las predeterminadas
  $settings = array_merge($defaults, $options);

  // Asegurarse de que el directorio de subida exista
  if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
      return ["success" => false, "message" => "No se pudo crear el directorio de destino."];
    }
  }

  // Validar si se cargó el archivo correctamente
  if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
    return ["success" => false, "message" => "Error al subir el archivo."];
  }

  // Validar tamaño del archivo
  if ($file['size'] > $settings['maxSize']) {
    return ["success" => false, "message" => "El archivo excede el tamaño máximo permitido de " . ($settings['maxSize'] / 1024 / 1024) . " MB."];
  }

  // Obtener información del archivo
  $fileInfo = pathinfo($file['name']);
  $fileExt  = strtolower($fileInfo['extension']);

  // Validar extensión del archivo
  if (!in_array($fileExt, $settings['imageSupported'])) {
    return ["success" => false, "message" => "La extensión .$fileExt no está permitida. Extensiones permitidas: " . implode(", ", $settings['imageSupported']) . "."];
  }

  // Generar un nombre único para el archivo si no se proporciona uno
  $fileName = $settings['fileName'] ? $settings['fileName'] : uniqid("img_", true);

  // Determinar la extensión final del archivo
  $finalExt = $settings['convertTo'] ? $settings['convertTo'] : $fileExt;
  $fileName .= ".$finalExt";

  // Ruta completa del archivo a guardar
  $filePath = rtrim($uploadDir, "/") . "/" . $fileName;

  // Mover el archivo al directorio temporal
  $tempPath = rtrim($uploadDir, "/") . "/temp_" . uniqid() . ".$fileExt";
  if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
    return ["success" => false, "message" => "No se pudo mover el archivo al directorio de destino."];
  }

  // Si es necesario convertir la imagen, hacerlo
  if ($settings['convertTo']) {
    $conversionResult = convert_image($tempPath, $filePath, $finalExt, $settings['optimize']);
    unlink($tempPath); // Eliminar archivo temporal
    if (!$conversionResult['success']) {
      return $conversionResult; // Devolver error en la conversión
    }
  } else {
    // Si no se convierte, simplemente renombrar el archivo
    rename($tempPath, $filePath);
  }

  // Retornar respuesta de éxito
  return ["success" => true, "message" => "Imagen subido con éxito.", "file_name" => $fileName, "file_path" => $filePath];

}

function convert_image($sourcePath, $destinationPath, $targetExt, $quality) {
  $targetExt = strtolower($targetExt);

  // Crear la imagen desde el archivo fuente
  $sourceImage = null;
  $sourceInfo  = getimagesize($sourcePath);
  if (!$sourceInfo) {
    return ["success" => false, "message" => "No se pudo leer la imagen para convertir."];
  }

  switch ($sourceInfo['mime']) {
    case 'image/jpeg':
      $sourceImage = imagecreatefromjpeg($sourcePath);
      break;
    case 'image/png':
      $sourceImage = @imagecreatefrompng($sourcePath);
      break;
    case 'image/webp':
      $sourceImage = imagecreatefromwebp($sourcePath);
      break;
    default:
      return ["success" => false, "message" => "Formato de imagen no soportado para conversión."];
  }

  // Convertir rango de calidad (0-10) según el formato de destino
  $quality = max(0, min(10, $quality)); // Asegurar que el rango esté entre 0 y 10
  if ($targetExt === "jpg" || $targetExt === "jpeg" || $targetExt === "webp") {
    $convertedQuality = $quality * 10; // Convertir a rango 0-100
  } elseif ($targetExt === "png") {
    $convertedQuality = 9 - round($quality); // Invertir para PNG (0 = sin compresión, 9 = máxima compresión)
  } else {
    imagedestroy($sourceImage);
    return ["success" => false, "message" => "Extensión de destino no soportada para conversión."];
  }

  // Convertir y guardar en el formato deseado
  switch ($targetExt) {
    case 'jpg':
    case 'jpeg':
      imagejpeg($sourceImage, $destinationPath, $convertedQuality);
      break;
    case 'png':
      imagepng($sourceImage, $destinationPath, $convertedQuality);
      break;
    case 'webp':
      imagewebp($sourceImage, $destinationPath, $convertedQuality);
      break;
    default:
      imagedestroy($sourceImage);
      return ["success" => false, "message" => "Extensión de destino no soportada para conversión."];
  }

  // Liberar memoria
  imagedestroy($sourceImage);

  return ["success" => true, "message" => "Imagen convertida exitosamente."];
}

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
