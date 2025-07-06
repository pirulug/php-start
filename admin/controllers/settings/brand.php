<?php

require_once "../../core.php";

$accessControl->check_access([1], SITE_URL . "/404.php");

if (!extension_loaded("imagick")) {
  $messageHandler->addMessage("Imagick no está instalado o se encuentra deshabilitado. Recomendamos instalarlo o habilitarlo para optimizar la generación del favicon y mejorar el procesamiento de imágenes.", "warning");
}

// Obtener datos de options
$query      = "SELECT option_key, option_value FROM options WHERE option_key IN ('favicon', 'white_logo', 'dark_logo', 'og_image')";
$optionsRaw = $connect->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

// Decodificar favicon
$st_favicon = json_decode($optionsRaw['favicon'] ?? '{}', true);

// Ruta de subida
$uploadPathLogo    = BASE_DIR . '/uploads/site/';
$uploadPathFavicon = BASE_DIR . '/uploads/site/favicons/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // =============== FAVICON ===============
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

      $newFavicon = json_encode([
        "android-chrome-192x192" => "android-chrome-192x192.png",
        "android-chrome-512x512" => "android-chrome-512x512.png",
        "apple-touch-icon"       => "apple-touch-icon.png",
        "favicon-16x16"          => "favicon-16x16.png",
        "favicon-32x32"          => "favicon-32x32.png",
        "favicon.ico"            => "favicon.ico",
        "webmanifest"            => "site.webmanifest"
      ], JSON_UNESCAPED_SLASHES);

      $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = 'favicon'");
      $stmt->bindParam(':value', $newFavicon);
      $stmt->execute();
      $messageHandler->addMessage("Favicon actualizado correctamente.", "success");
      header("Location: brand.php");
      exit();
    }
  }

  // =============== LOGO CLARO ===============
  if (!empty($_FILES['st_whitelogo']) && $_FILES['st_whitelogo']['size'] > 0) {
    $upWhiteLogo  = $_FILES['st_whitelogo'];
    $whitelogo    = upload_image($upWhiteLogo, $uploadPathLogo, 320, 71, [
      'convertTo' => 'webp',
      'prefix'    => 'st_logo_light_'
    ]);
    $st_whitelogo = $whitelogo['file_name'];

    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = 'white_logo'");
    $stmt->bindParam(':value', $st_whitelogo);
    $stmt->execute();

    if (!empty($optionsRaw['white_logo']) && file_exists($uploadPathLogo . $optionsRaw['white_logo'])) {
      unlink($uploadPathLogo . $optionsRaw['white_logo']);
    }

    $messageHandler->addMessage("Logo claro actualizado correctamente.", "success");
    header("Location: brand.php");
    exit();
  }

  // =============== LOGO OSCURO ===============
  if (!empty($_FILES['st_darklogo']) && $_FILES['st_darklogo']['size'] > 0) {
    $upDarkLogo  = $_FILES['st_darklogo'];
    $darklogo    = upload_image($upDarkLogo, $uploadPathLogo, 320, 71, [
      'convertTo' => 'webp',
      'prefix'    => 'st_logo_dark_'
    ]);
    $st_darklogo = $darklogo['file_name'];

    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = 'dark_logo'");
    $stmt->bindParam(':value', $st_darklogo);
    $stmt->execute();

    if (!empty($optionsRaw['dark_logo']) && file_exists($uploadPathLogo . $optionsRaw['dark_logo'])) {
      unlink($uploadPathLogo . $optionsRaw['dark_logo']);
    }

    $messageHandler->addMessage("Logo oscuro actualizado correctamente.", "success");
    header("Location: brand.php");
    exit();
  }

  // =============== OG IMAGE ===============
  if (!empty($_FILES['st_og_image']) && $_FILES['st_og_image']['size'] > 0) {
    $upOGImage   = $_FILES['st_og_image'];
    $ogImage     = upload_image($upOGImage, $uploadPathLogo, 1200, 630, [
      'convertTo' => 'webp',
      'prefix'    => 'og_image_'
    ]);
    $st_og_image = $ogImage['file_name'];

    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = 'og_image'");
    $stmt->bindParam(':value', $st_og_image);
    $stmt->execute();

    if (!empty($optionsRaw['og_image']) && file_exists($uploadPathLogo . $optionsRaw['og_image'])) {
      unlink($uploadPathLogo . $optionsRaw['og_image']);
    }

    $messageHandler->addMessage("Imagen Open Graph actualizada correctamente.", "success");
    header("Location: brand.php");
    exit();
  }
}

/* ========== Theme config ========= */
$theme_title = "Brand";
$theme_path  = "brand";
include BASE_DIR_ADMIN . "/views/settings/brand.view.php";
/* ================================= */