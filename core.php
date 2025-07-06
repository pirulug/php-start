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
foreach (glob(BASE_DIR . '/functions/*.php') as $file) {
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
$log = new Log($connect, BASE_DIR . "/logs");

// Acción del usuario
// $log->logAction(1, 'Login', 'El usuario ingresó al sistema');

// Log de error
// $log->log('error', 'No se pudo conectar a la API');

// Log de debug
// $log->log('debug', 'Variable $x no es válida', json_encode(['x' => null]));

// Otro tipo de log personalizado
// $log->log('notificaciones', 'Correo enviado a usuario', 'user@example.com');

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

// =============================================================================
// OPTIONS
// =============================================================================

$query = "SELECT option_key, option_value FROM options";
$stmt  = $connect->prepare($query);
$stmt->execute();
$options_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

$options = [];
foreach ($options_raw as $row) {
  $options[$row['option_key']] = $row['option_value'];
}

define('SITE_NAME', $options['site_name'] ?? 'Php Start');
define('SITE_URL', $options['site_url'] ?? 'http://php-start.test');
define('SITE_URL_ADMIN', $options['site_url']."/admin" ?? 'http://php-start.test/admin');
define('SITE_DESCRIPTION', $options['site_description'] ?? '');
define('SITE_KEYWORDS', $options['site_keywords'] ?? '');

$st_favicon = json_decode($options["favicon"]);

// $brd_android_chrome_192x192 = $st_favicon["android-chrome-192x192"];
// $brd_android_chrome_512x512 = $st_favicon["android-chrome-512x512"];
// $brd_apple_touch_icon       = $st_favicon["apple-touch-icon"];
// $brd_favicon_16x16          = $st_favicon["favicon-16x16"];
// $brd_favicon_32x32          = $st_favicon["favicon-32x32"];
// $brd_favicon                = $st_favicon["favicon"];
// $brd_webmanifest            = $st_favicon["webmanifest"];

$st_darklogo  = $options['dark_logo'] ?? 'default-dark.png';
$st_whitelogo = $options['white_logo'] ?? 'default-white.png';
$st_og_image  = $options['og_image'] ?? 'default-og.png';

// =============================================================================
// URL Helper
// =============================================================================
$url_static = new UrlHelper(SITE_URL);