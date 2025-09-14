<?php

if (!file_exists(__DIR__ . "/../config.php")) {
  die("Te falta el archivo config.php");
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . "/../config.php";


foreach (glob(BASE_DIR . '/core/config/*.php') as $file) {
  require_once $file;
}

foreach (glob(BASE_DIR . '/core/libs/*.php') as $file) {
  require_once $file;
}

foreach (glob(BASE_DIR . '/core/helpers/*.php') as $file) {
  require_once $file;
}

// =============================================================================
// Conexión a base de datos
// =============================================================================
$db      = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$connect = $db->getConnection();

if (!$connect) {
  die("Error de conexión a la base de datos");
}

// =============================================================================
// Mensajes
// =============================================================================
$notifier = new notifier();

// =============================================================================
// Encryption
// =============================================================================
$cipher = new Cipher(ENCRYPT_METHOD, ENCRYPT_KEY, ENCRYPT_IV);

// echo $encryption->encrypt("admin123");
// exit();

// =============================================================================
// Obtener Información de usuario
// =============================================================================
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $user_session = get_user_session_information($connect, $_SESSION["user_id"]);
} else {
  $user_session = null;
}

// =============================================================================
// Access Control
// =============================================================================
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $accessControl = new AccessControl($_SESSION["signin"], $user_session);
} else {
  $accessControl = new AccessControl(false, null);
}

// =============================================================================
// Cargar Configuración del sitio
// =============================================================================

$config  = new SiteConfig($connect);
$favicon = json_decode($config->get("favicon"), true);

// =============================================================================
// Template Engine
// =============================================================================

// Instanciar motor de plantillas
$theme = new TemplateEngine();

// Variables globales para todas las vistas
$theme->setGlobals([
  // 'static_url'     => $static_url,
  'user_session'   => $user_session,
  'notifier'       => $notifier,
  'accessControl'  => $accessControl,
  'cipher'         => $cipher,
  'config'         => $config,
  'favicon'        => $favicon,
  'SITE_URL_ADMIN' => SITE_URL_ADMIN,
  'SITE_NAME'      => SITE_NAME,
  'SITE_URL'       => SITE_URL
]);
