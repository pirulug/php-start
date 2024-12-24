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
  }

  // Dark logo
  $uploadPathLogo = BASE_DIR . '/uploads/site/';

  if ($upWhiteLogo['size'] > 0) {
    $whitelogo    = upload_image($upWhiteLogo, $uploadPathLogo, ['convertTo' => 'webp', 'fileName' => 'st_whitelogo']);
    $st_whitelogo = $whitelogo['file_name'];
  }

  if ($upDarkLogo['size'] > 0) {
    $darkLogo    = upload_image($upDarkLogo, $uploadPathLogo, ['convertTo' => 'webp', 'fileName' => 'st_darklogo']);
    $st_darklogo = $darkLogo['file_name'];
  }

  // Variables
  $fields = [];
  $params = [];

  if ($upFavicon['size'] > 0) {
    $fields[]              = "st_favicon = :st_favicon";
    $params[':st_favicon'] = $st_favicon;
  }
  if ($upWhiteLogo['size'] > 0) {
    $fields[]                = "st_whitelogo = :st_whitelogo";
    $params[':st_whitelogo'] = $st_whitelogo;
  }
  if ($upDarkLogo['size'] > 0) {
    $fields[]               = "st_darklogo = :st_darklogo";
    $params[':st_darklogo'] = $st_darklogo;
  }

  // Si no hay campos para actualizar, termina el script
  if (empty($fields)) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    $messageHandler->addMessage('No hay datos para actualizar.', 'warning');
    exit();
  }

  // Construcción de la consulta
  $query = "UPDATE brand SET " . implode(", ", $fields);

  // Preparar y ejecutar la consulta
  $stmt = $connect->prepare($query);

  foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
  }

  if ($stmt->execute()) {
    echo "Actualización exitosa.";
  } else {
    var_dump($stmt->errorInfo());
  }

  $messageHandler->addMessage("Se actualizo las imagenes de manera correcta", "success");
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
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