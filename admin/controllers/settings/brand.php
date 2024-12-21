<?php

require_once "../../core.php";

$accessControl->require_login(SITE_URL_ADMIN . "/controllers/login.php");
$accessControl->check_access([1], SITE_URL . "/404.php");

// Saber si existe el METHOD POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $uploadsPath        = BASE_DIR . '/uploads/site/';
  $faviconUploadsPath = $uploadsPath . "favicons/";

  $faviconFile = $_FILES['st_favicon'];

  if (mime_content_type($_FILES['st_favicon']['tmp_name']) !== 'image/png') {
    die("Error: El archivo subido debe ser un PNG.");
  }

  if (!is_dir($faviconUploadsPath)) {
    mkdir($faviconUploadsPath, 0777, true);
  }

  move_uploaded_file($faviconFile['tmp_name'], $faviconUploadsPath . $faviconFile['name']);

  $generator = new FaviconGenerator($faviconUploadsPath);
  $generator->generate($faviconUploadsPath . $faviconFile['name']);

  unlink($faviconUploadsPath . $faviconFile['name']);

  // $st_favicon   = uploadSiteImage('st_favicon', $_POST['st_favicon_save'], $uploadsPath);
  $st_favicon   = json_encode([
    "android-chrome-192x192" => "android-chrome-192x192.png",
    "android-chrome-512x512" => "android-chrome-512x512.png",
    "apple-touch-icon"       => "apple-touch-icon.png",
    "favicon-16x16"          => "favicon-16x16.png",
    "favicon-32x32"          => "favicon-32x32.png",
    "favicon"                => "favicon.ico",
    "webmanifest"            => "site.webmanifest"
  ]);
  $st_whitelogo = uploadSiteImage('st_whitelogo', $_POST['st_whitelogo_save'], $uploadsPath);
  $st_darklogo  = uploadSiteImage('st_darklogo', $_POST['st_darklogo_save'], $uploadsPath);

  $query = "UPDATE brand SET
                  st_favicon = :st_favicon,
                  st_whitelogo = :st_whitelogo,
                  st_darklogo = :st_darklogo";

  $statement = $connect->prepare($query);
  $statement->bindParam(':st_favicon', $st_favicon);
  $statement->bindParam(':st_whitelogo', $st_whitelogo);
  $statement->bindParam(':st_darklogo', $st_darklogo);
  $statement->execute();

  $messageHandler->addMessage("Se actualizo las imagenes de manera correcta", "success");
  // header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
}

// Obtener datos
$querySelect = "SELECT * FROM brand";
$brand       = $connect->query($querySelect)->fetch(PDO::FETCH_OBJ);

$st_favicon = json_decode($brand->st_favicon);

$android_chrome_192x192 = $st_favicon->{'android-chrome-192x192'};
$android_chrome_512x512 = $st_favicon->{'android-chrome-512x512'};
$apple_touch_icon       = $st_favicon->{'apple-touch-icon'};
$favicon_16x16          = $st_favicon->{'favicon-16x16'};
$favicon_32x32          = $st_favicon->{'favicon-32x32'};
$favicon                = $st_favicon->favicon;
$webmanifest            = $st_favicon->webmanifest;

/* ========== Theme config ========= */
$theme_title = "Brand";
$theme_path  = "brand";
include BASE_DIR_ADMIN . "/views/settings/brand.view.php";
/* ================================= */