<?php

function upload_image($file, $uploadDir, $options = []) {
  // Configuración predeterminada
  $defaults = [
    "imageSupported" => ["jpg", "png", "jpeg", "webp"], // Extensiones permitidas
    "maxSize" => 2 * 1024 * 1024,                      // Tamaño máximo: 2 MB
    "convertTo" => null,                               // Convertir a: null, "jpg", "png", "webp"
    "optimize" => 7,                                   // Calidad (0 a 10)
    "fileName" => null,                                // Nombre del archivo personalizado
    "prefix" => "img_",                                    // Prefijo para el nombre del archivo
    "resize" => []                                     // Tamaños a redimensionar (ejemplo: ['small' => [150, 150], 'medium' => [300, 300]])
  ];
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
    return ["success" => false, "message" => "La extensión .$fileExt no está permitida."];
  }

  // Generar un nombre único para el archivo si no se proporciona uno
  $fileName = $settings['fileName'] ? $settings['fileName'] : uniqid($settings['prefix'], true);
  $finalExt = $settings['convertTo'] ? $settings['convertTo'] : $fileExt;
  $fileName .= ".$finalExt";
  $filePath = rtrim($uploadDir, "/") . "/" . $fileName;

  // Mover el archivo al directorio temporal
  $tempPath = rtrim($uploadDir, "/") . "/temp_" . uniqid() . ".$fileExt";
  if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
    return ["success" => false, "message" => "No se pudo mover el archivo al directorio de destino."];
  }

  // Convertir o procesar la imagen principal
  $conversionResult = process_image($tempPath, $filePath, $finalExt, $settings['optimize']);
  if (!$conversionResult['success']) {
    unlink($tempPath); // Eliminar archivo temporal
    return $conversionResult;
  }

  // Generar imágenes redimensionadas si es necesario
  $resizedImages = [];
  if (!empty($settings['resize'])) {
    foreach ($settings['resize'] as $key => $dimensions) {
      $resizedPath  = rtrim($uploadDir, "/") . "/" . $fileName . "_$key.$finalExt";
      $resizeResult = resize_image($tempPath, $resizedPath, $dimensions[0], $dimensions[1], $settings['optimize']);
      if ($resizeResult['success']) {
        $resizedImages[$key] = [
          "file_name" => $resizeResult['file_name'],
          "file_path" => $resizeResult['path']
        ];
      } else {
        unlink($tempPath);
        return $resizeResult; // Detener en caso de error
      }
    }
  }

  unlink($tempPath); // Eliminar archivo temporal

  return [
    "success" => true,
    "message" => "Imagen subida y procesada con éxito.",
    "file_name" => $fileName,
    "file_path" => $filePath,
    "resized_images" => $resizedImages
  ];
}

function process_image($sourcePath, $destinationPath, $targetExt, $quality) {
  // Usar Imagick si está instalado
  if (class_exists('Imagick')) {
    return process_with_imagick($sourcePath, $destinationPath, $targetExt, $quality);
  }

  // Retroceder a GD si Imagick no está disponible
  return convert_image($sourcePath, $destinationPath, $targetExt, $quality);
}

function process_with_imagick($sourcePath, $destinationPath, $targetExt, $quality) {
  try {
    $imagick = new Imagick($sourcePath);
    $imagick->setImageFormat($targetExt);
    $imagick->setImageCompressionQuality($quality * 10); // Escala a 0-100
    $imagick->writeImage($destinationPath);
    $imagick->destroy();

    return ["success" => true, "message" => "Imagen procesada con Imagick."];
  } catch (Exception $e) {
    return ["success" => false, "message" => "Error al procesar la imagen con Imagick: " . $e->getMessage()];
  }
}

function convert_image($sourcePath, $destinationPath, $targetExt, $quality) {
  $sourceInfo = getimagesize($sourcePath);
  if (!$sourceInfo) {
    return ["success" => false, "message" => "No se pudo leer la imagen."];
  }

  $sourceImage = null;
  switch ($sourceInfo['mime']) {
    case 'image/jpeg':
      $sourceImage = imagecreatefromjpeg($sourcePath);
      break;
    case 'image/png':
      $sourceImage = imagecreatefrompng($sourcePath);
      break;
    case 'image/webp':
      $sourceImage = imagecreatefromwebp($sourcePath);
      break;
    default:
      return ["success" => false, "message" => "Formato no soportado para GD."];
  }

  $quality          = max(0, min(10, $quality));
  $convertedQuality = ($targetExt === "png") ? 9 - round($quality) : $quality * 10;

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
      return ["success" => false, "message" => "Formato de destino no soportado para GD."];
  }

  imagedestroy($sourceImage);
  return ["success" => true, "message" => "Imagen procesada con GD."];
}

function resize_image($sourcePath, $destinationPath, $width, $height, $quality) {

  $fileName = basename($destinationPath);

  // Usar Imagick si está disponible
  if (class_exists('Imagick')) {
    try {
      $image = new Imagick($sourcePath);
      // $image->thumbnailImage($width, $height, true); // Mantener proporciones
      $image->cropThumbnailImage($width, $height); // Ajusta y recorta para cubrir el área
      $image->setImageCompressionQuality($quality * 10); // Calidad 0-100
      $image->writeImage($destinationPath);
      $image->destroy();

      return [
        "success" => true,
        "message" => "Imagen redimensionada con Imagick.",
        "path" => $destinationPath,
        "file_name" => $fileName
      ];
    } catch (Exception $e) {
      return ["success" => false, "message" => "Error al redimensionar con Imagick: " . $e->getMessage()];
    }
  }

  // Usar GD como alternativa
  $sourceInfo = getimagesize($sourcePath);
  if (!$sourceInfo) {
    return ["success" => false, "message" => "No se pudo leer la imagen para redimensionar."];
  }

  $sourceImage = null;
  switch ($sourceInfo['mime']) {
    case 'image/jpeg':
      $sourceImage = imagecreatefromjpeg($sourcePath);
      break;
    case 'image/png':
      $sourceImage = imagecreatefrompng($sourcePath);
      break;
    case 'image/webp':
      $sourceImage = imagecreatefromwebp($sourcePath);
      break;
    default:
      return ["success" => false, "message" => "Formato de imagen no soportado por GD."];
  }

  $originalWidth  = $sourceInfo[0];
  $originalHeight = $sourceInfo[1];

  // 1. Redimensionar la imagen usando el ancho dado y un alto calculado automáticamente
  $newWidth  = $width;
  $newHeight = (int) ($originalHeight * ($width / $originalWidth));

  // Crear la imagen redimensionada
  $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

  // Mantener la transparencia para imágenes PNG y WebP
  if ($sourceInfo['mime'] == 'image/png' || $sourceInfo['mime'] == 'image/webp') {
    imagesavealpha($resizedImage, true);
    $transparentColor = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127); // Transparente
    imagefill($resizedImage, 0, 0, $transparentColor);
  }

  // Redimensionar la imagen
  imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

  // 2. Realizar el corte desde el centro de la imagen redimensionada
  $cropX = ($newWidth - $width) / 2;
  $cropY = ($newHeight - $height) / 2;

  // Crear la imagen final recortada
  $finalImage = imagecreatetruecolor($width, $height);

  // Mantener la transparencia para imágenes PNG y WebP
  if ($sourceInfo['mime'] == 'image/png' || $sourceInfo['mime'] == 'image/webp') {
    imagesavealpha($finalImage, true);
    $transparentColor = imagecolorallocatealpha($finalImage, 0, 0, 0, 127); // Transparente
    imagefill($finalImage, 0, 0, $transparentColor);
  }

  // Copiar el área recortada desde la imagen redimensionada
  imagecopy($finalImage, $resizedImage, 0, 0, $cropX, $cropY, $width, $height);

  // 3. Guardar la imagen recortada
  $result = false;
  switch ($sourceInfo['mime']) {
    case 'image/jpeg':
      $result = imagejpeg($finalImage, $destinationPath, $quality * 10); // Calidad 0-100
      break;
    case 'image/png':
      $result = imagepng($finalImage, $destinationPath, 9 - round($quality / 10)); // Calidad 0-9
      break;
    case 'image/webp':
      $result = imagewebp($finalImage, $destinationPath, $quality * 10); // Calidad 0-100
      break;
  }

  // Liberar recursos
  imagedestroy($sourceImage);
  imagedestroy($resizedImage);
  imagedestroy($finalImage);

  if ($result) {
    return [
      "success" => true,
      "message" => "Imagen redimensionada y recortada con GD.",
      "path" => $destinationPath,
      "file_name" => $fileName
    ];
  } else {
    return ["success" => false, "message" => "Error al guardar la imagen redimensionada con GD."];
  }
}