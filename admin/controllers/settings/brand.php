<?php

require_once "../../core.php";

$accessControl->require_login(SITE_URL_ADMIN . "/controllers/login.php");
$accessControl->check_access([1], SITE_URL . "/404.php");

if (!extension_loaded("imagick")) {
  $messageHandler->addMessage("Imagick no está instalado o se encuentra deshabilitado. Recomendamos instalarlo o habilitarlo para optimizar la generación del favicon y mejorar el procesamiento de imágenes.", "warning");
}

// Saber si existe el METHOD POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $upFavicon   = $_FILES['st_favicon'];
  $upWhiteLogo = $_FILES['st_whitelogo'];
  $upDarkLogo  = $_FILES['st_darklogo'];

  $upFaviconSave   = $_POST['st_favicon_save'];
  $upWhiteLogoSave = $_POST['st_whitelogo_save'];
  $upDarkLogoSave  = $_POST['st_darklogo_save'];

  // Obtener datos
  // $brand = $connect->query("SELECT * FROM brand")->fetch(PDO::FETCH_OBJ);

  // Favicon
  if ($upFavicon['size'] > 0) {
    $uploadPathFavicon = BASE_DIR . '/uploads/site/favicons/';
    if (!file_exists($uploadPathFavicon)) {
      mkdir($uploadPathFavicon, 0777, true);
    }

    if (mime_content_type($upFavicon['tmp_name']) !== 'image/png') {
      $messageHandler->addMessage("El archivo debe ser un PNG válido.", "danger");
    }

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
  } else {
    $st_favicon = $upFaviconSave;
  }

  // Dark logo
  $uploadPathLogo = BASE_DIR . '/uploads/site/';

  if ($upWhiteLogo['size'] > 0) {
    $whitelogo    = upload_image($upWhiteLogo, $uploadPathLogo, ['convertTo' => 'webp', 'prefix' => "st_logo_light_"]);
    $st_whitelogo = $whitelogo['file_name'];

    // Eliminar logo anterior
    unlink($uploadPathLogo . $brand->st_whitelogo);
  } else {
    $st_whitelogo = $upWhiteLogoSave;
  }

  if ($upDarkLogo['size'] > 0) {
    $darkLogo    = upload_image($upDarkLogo, $uploadPathLogo, ['convertTo' => 'webp', 'prefix' => "st_logo_dark_"]);
    $st_darklogo = $darkLogo['file_name'];

    // Eliminar logo anterior
    unlink($uploadPathLogo . $brand->st_darklogo);
  } else {
    $st_darklogo = $upDarkLogoSave;
  }

  // Actualizar datos
  $queryUpdate = "UPDATE brand SET st_favicon = :st_favicon, st_whitelogo = :st_whitelogo, st_darklogo = :st_darklogo";
  $stmt        = $connect->prepare($queryUpdate);
  $stmt->bindParam(':st_favicon', $st_favicon);
  $stmt->bindParam(':st_whitelogo', $st_whitelogo);
  $stmt->bindParam(':st_darklogo', $st_darklogo);

  if ($stmt->execute()) {
    $messageHandler->addMessage("Se actualizo las imagenes de manera correcta", "success");
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
  } else {
    $messageHandler->addMessage("Ocurrio un error al actualizar las imagenes: " . $stmt->errorInfo(), "danger");
  }
}

// Obtener datos
$querySelect = "SELECT * FROM brand";
$brand       = $connect->query($querySelect)->fetch(PDO::FETCH_OBJ);

$st_favicon = json_decode($brand->st_favicon);

/* ========== Theme config ========= */
$theme_title = "Brand";
$theme_path  = "brand";
include BASE_DIR_ADMIN . "/views/settings/brand.view.php";
/* ================================= */