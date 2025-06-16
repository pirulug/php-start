<?php

require_once "../../core.php";

$accessControl->check_access([1], SITE_URL . "/404.php");

if (!extension_loaded("imagick")) {
  $messageHandler->addMessage("Imagick no está instalado o se encuentra deshabilitado. Recomendamos instalarlo o habilitarlo para optimizar la generación del favicon y mejorar el procesamiento de imágenes.", "warning");
}

// Obtener datos
$querySelect = "SELECT * FROM brand";
$brand       = $connect->query($querySelect)->fetch(PDO::FETCH_OBJ);

$st_favicon = json_decode($brand->st_favicon);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $uploadPathLogo    = BASE_DIR . '/uploads/site/';
  $uploadPathFavicon = BASE_DIR . '/uploads/site/favicons/';

  // Procesar FAVICON si se ha subido
  if (!empty($_FILES['st_favicon']) && $_FILES['st_favicon']['size'] > 0) {
    if (!file_exists($uploadPathFavicon)) {
      mkdir($uploadPathFavicon, 0777, true);
    }

    $upFavicon = $_FILES['st_favicon'];

    if (mime_content_type($upFavicon['tmp_name']) !== 'image/png') {
      $messageHandler->addMessage("El archivo debe ser un PNG válido.", "danger");
    } else {
      move_uploaded_file($upFavicon["tmp_name"], $uploadPathFavicon . $upFavicon['name']);

      $generator = new FaviconGenerator($uploadPathFavicon);
      $generator->generate($uploadPathFavicon . $upFavicon['name']);
      unlink($uploadPathFavicon . $upFavicon['name']);

      $st_favicon = json_encode([
        "android-chrome-192x192" => "android-chrome-192x192.png",
        "android-chrome-512x512" => "android-chrome-512x512.png",
        "apple-touch-icon"       => "apple-touch-icon.png",
        "favicon-16x16"          => "favicon-16x16.png",
        "favicon-32x32"          => "favicon-32x32.png",
        "favicon"                => "favicon.ico",
        "webmanifest"            => "site.webmanifest"
      ], JSON_UNESCAPED_SLASHES);
    }

    $query = "UPDATE brand SET st_favicon = :st_favicon";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':st_favicon', $st_favicon);

    if (!$stmt->execute()) {
      $messageHandler->addMessage("Ocurrió un error al actualizar el favicon: " . implode(" | ", $stmt->errorInfo()), "danger");
    } else {
      $messageHandler->addMessage("Favicon actualizado correctamente.", "success");
      header("Location: brand.php");
      exit();
    }
  }

  // Procesar LOGO CLARO si se ha subido
  if (!empty($_FILES['st_whitelogo']) && $_FILES['st_whitelogo']['size'] > 0) {
    $upWhiteLogo  = $_FILES['st_whitelogo'];
    $whitelogo    = upload_image(
      $upWhiteLogo, 
      $uploadPathLogo, 
      320, 
      71, 
      [
        'convertTo' => 'webp', 
        'prefix' => 'st_logo_light_'
      ]);
    $st_whitelogo = $whitelogo['file_name'];

    $query = "UPDATE brand SET st_whitelogo = :st_whitelogo";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':st_whitelogo', $st_whitelogo);

    if (!$stmt->execute()) {
      $messageHandler->addMessage("Ocurrió un error al actualizar el logo claro: " . implode(" | ", $stmt->errorInfo()), "danger");
    } else {
      $messageHandler->addMessage("Logo claro actualizado correctamente.", "success");
      header("Location: brand.php");

      // Eliminar logo anterior
      if (!empty($brand->st_whitelogo) && file_exists($uploadPathLogo . $brand->st_whitelogo)) {
        unlink($uploadPathLogo . $brand->st_whitelogo);
      }

      exit();
    }

  }

  // Procesar LOGO OSCURO si se ha subido
  if (!empty($_FILES['st_darklogo']) && $_FILES['st_darklogo']['size'] > 0) {
    $upDarkLogo  = $_FILES['st_darklogo'];
    $darklogo    = upload_image(
      $upDarkLogo, 
      $uploadPathLogo,
      320,
      71,
      [
        'convertTo' => 'webp',
        'prefix'    => 'st_logo_dark_'
      ]);
    $st_darklogo = $darklogo['file_name'];

    $query = "UPDATE brand SET st_darklogo = :st_darklogo";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':st_darklogo', $st_darklogo);

    if (!$stmt->execute()) {
      $messageHandler->addMessage("Ocurrió un error al actualizar el logo oscuro: " . implode(" | ", $stmt->errorInfo()), "danger");
    } else {
      $messageHandler->addMessage("Logo oscuro actualizado correctamente.", "success");
      header("Location: brand.php");

      // Eliminar logo anterior
      if (!empty($brand->st_darklogo) && file_exists($uploadPathLogo . $brand->st_darklogo)) {
        unlink($uploadPathLogo . $brand->st_darklogo);
      }

      exit();
    }
  }

  // Procesar OPEN GRAPH IMAGE si se ha subido
  if (!empty($_FILES['st_og_image']) && $_FILES['st_og_image']['size'] > 0) {
    $upOGImage   = $_FILES['st_og_image'];
    $ogImage     = upload_image(
      $upOGImage, 
      $uploadPathLogo,
      1200,
      630,
      [
        'convertTo' => 'webp',
        'prefix'    => 'og_image_'
      ]);
    $st_og_image = $ogImage['file_name'];

    $query = "UPDATE brand SET st_og_image = :st_og_image";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':st_og_image', $st_og_image);

    if (!$stmt->execute()) {
      $messageHandler->addMessage("Ocurrió un error al actualizar la imagen Open Graph: " . implode(" | ", $stmt->errorInfo()), "danger");
    } else {
      $messageHandler->addMessage("Imagen Open Graph actualizada correctamente.", "success");
      header("Location: brand.php");

      // Eliminar imagen anterior
      if (!empty($brand->st_og_image) && file_exists($uploadPathLogo . $brand->st_og_image)) {
        unlink($uploadPathLogo . $brand->st_og_image);
      }

      exit();
    }

  }
}

/* ========== Theme config ========= */
$theme_title = "Brand";
$theme_path  = "brand";
include BASE_DIR_ADMIN . "/views/settings/brand.view.php";
/* ================================= */