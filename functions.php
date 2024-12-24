<?php

function check_access($connect) {
  $sentence = $connect->query("SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' AND user_status = 1 LIMIT 1");
  $row      = $sentence->fetch(PDO::FETCH_ASSOC);
  return $row;
}

function get_user_session_information($connect) {
  $sentence = $connect->query("SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1");
  $sentence = $sentence->fetch(PDO::FETCH_OBJ);
  return ($sentence) ? $sentence : false;
}

function get_image_url($imageData, $baseUrl, $desiredWidth = null) {
  // Decodificar el JSON del campo course_image
  $images = json_decode($imageData, true);

  // Validar que la decodificación fue exitosa
  if (!$images || !isset($images['original'])) {
    return $baseUrl . "default.webp"; // URL de la imagen por defecto
  }

  // Si la imagen es "default.webp", devolverla sin importar el tamaño deseado
  if ($images['original'] === "default.webp") {
    return $baseUrl . "default.webp";
  }

  // Si no se especifica un tamaño deseado, devolver la original
  if ($desiredWidth === null) {
    return $baseUrl . "" . $images['original'];
  }

  // Si hay imágenes redimensionadas, buscar la más cercana al tamaño deseado
  if (isset($images['resized']) && is_array($images['resized'])) {
    $closestSize = null;
    $closestFile = null;

    foreach ($images['resized'] as $size => $fileName) {
      if ($closestSize === null || abs($desiredWidth - $size) < abs($desiredWidth - $closestSize)) {
        $closestSize = $size;
        $closestFile = $fileName;
      }
    }

    // Si se encuentra una imagen redimensionada adecuada, devolver su URL
    if ($closestFile) {
      return $baseUrl . "" . $closestFile;
    }
  }

  // Si no hay redimensionadas, devolver la original
  return $baseUrl . "" . $images['original'];
}