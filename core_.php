<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// require_once __DIR__ . '/config.php';

require_once __DIR__ . '/core/init.php';


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
// require BASE_DIR . '/core/options.core.php';
$siteOptions = new SiteOptions($connect);

$st_android_chrome_192x192 = $siteOptions->getFavicon('android-chrome-192x192');
$st_android_chrome_512x512 = $siteOptions->getFavicon('android-chrome-512x512');
$st_apple_touch_icon       = $siteOptions->getFavicon('apple-touch-icon');
$st_favicon_16x16          = $siteOptions->getFavicon('favicon-16x16');
$st_favicon_32x32          = $siteOptions->getFavicon('favicon-32x32');
$st_favicon                = $siteOptions->getFavicon('favicon.ico');
$st_webmanifest            = $siteOptions->getFavicon('webmanifest');

$st_darklogo  = $siteOptions->getDarkLogo();
$st_whitelogo = $siteOptions->getWhiteLogo();
$st_og_image  = $siteOptions->getOgImage();

// =============================================================================
// URL Helper
// =============================================================================
$static_url = new StaticUrl(SITE_URL);
$site_url   = new SiteUrl(SITE_URL);
