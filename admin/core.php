<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../config.php';
// require_once __DIR__ . '/functions.php';

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
