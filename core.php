<?php

if (!file_exists("config.php")) {
  header("Location: install/");
  exit();
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once "config.php";
require_once "functions.php";

// Config
foreach (glob(BASE_DIR . '/config/*.php') as $file) {
  require_once $file;
}

// Libs
foreach (glob(BASE_DIR . '/libs/*.php') as $file) {
  require_once $file;
}

// Conectar BD
$db      = new DataBase(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$connect = $db->getConnection();

if (!$connect) {
  header('Location: ' . SITE_URL . '/admin/controller/error.php');
  exit();
}

// Template Engine
$theme = new TemplateEngine();

if (isset($_SESSION["user_name"])) {
  $user_session = get_user_session_information($connect);
}

// Access Control
$accessControl = new AccessControl();

// Encryption
$encryption = new Encryption(ENCRYPT_METHOD, SECRET_KEY, SECRET_IV);

// Mensajes
$messageHandler = new MessageHandler();

// Obetener los logos y favicon
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

// Obtener Config General
$settings = $connect->query("SELECT * FROM settings")->fetch(PDO::FETCH_OBJ);

// User log
$log = new Log($connect, BASE_DIR . "/log/actions.log");

// URL Helper
$url = new UrlHelper(SITE_URL);

// Visit Counter
$visitCounter = new VisitCounter($connect);
// $visitCounter->register_visit($_SERVER['REQUEST_URI']);
