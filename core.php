<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/config.php';

// =============================================================================
// Configuración
// =============================================================================

foreach (glob(BASE_DIR . '/config/*.php') as $file) {
  require_once $file;
}

// =============================================================================
// Libs
// =============================================================================

foreach (glob(BASE_DIR . '/libs/*.php') as $file) {
  require_once $file;
}

// =============================================================================
// Helpers
// =============================================================================
foreach (glob(BASE_DIR . '/helpers/*.php') as $file) {
  require_once $file;
}

// =============================================================================
// CONNECT TO DATABASE
// =============================================================================
$db      = new DataBase(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$connect = $db->getConnection();

if (!$connect) {
  header('Location: ' . SITE_URL . '/admin/controller/error.php');
  exit();
}

// =============================================================================
// Template Engine
// =============================================================================
$theme = new TemplateEngine();

// =============================================================================
// Encryption
// =============================================================================
$encryption = new Encryption(ENCRYPT_METHOD, SECRET_KEY, SECRET_IV);

// =============================================================================
// Mensajes
// =============================================================================
$messageHandler = new MessageHandler();

// =============================================================================
// User log
// =============================================================================
$log = new Log($connect, BASE_DIR . "/log/actions.log");

// =============================================================================
// Visit Counter
// =============================================================================
$visitCounter = new VisitCounter($connect);

// =============================================================================
// Obtener Información de usuario
// =============================================================================
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $user_session = get_user_session_information($connect, $_SESSION["user_id"]);
}

// =============================================================================
// Access Control
// =============================================================================
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $accessControl = new AccessControl($_SESSION["signin"], $user_session);
} else {
  $accessControl = new AccessControl(false, null);
}

$brand      = $connect->query("SELECT * FROM brand")->fetch(PDO::FETCH_OBJ);
$st_favicon = json_decode($brand->st_favicon, true);

$brd_android_chrome_192x192 = $st_favicon["android-chrome-192x192"];
$brd_android_chrome_512x512 = $st_favicon["android-chrome-512x512"];
$brd_apple_touch_icon       = $st_favicon["apple-touch-icon"];
$brd_favicon_16x16          = $st_favicon["favicon-16x16"];
$brd_favicon_32x32          = $st_favicon["favicon-32x32"];
$brd_favicon                = $st_favicon["favicon"];
$brd_webmanifest            = $st_favicon["webmanifest"];

$st_darklogo  = $brand->st_darklogo;
$st_whitelogo = $brand->st_whitelogo;
$st_og_image  = $brand->st_og_image;

$settings = $connect->query("SELECT * FROM settings")->fetch(PDO::FETCH_OBJ);

$url_static = new UrlHelper(SITE_URL);