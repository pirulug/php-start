<?php

// Configuración base
require_once BASE_DIR . "/core/config/path.config.php";
require_once BASE_DIR . "/core/config/security.config.php";
require_once BASE_DIR . "/core/config/cache.config.php";

// Functions
require_once BASE_DIR . "/core/functions/load_routes.php";
require_once BASE_DIR . "/core/functions/get_user_session.php";
require_once BASE_DIR . "/core/functions/view_blocks.php";

// Libs
loadCoreFiles('libs');

// Helpers
loadCoreFiles('helpers');

// Base de datos
$db = (new DataBase())
  ->host(DB_HOST)
  ->name(DB_NAME)
  ->user(DB_USER)
  ->password(DB_PASS);

$connect = $db->getConnection();

// Notifier
$notifier = new Notifier();

// Cipher
$cipher = (new Cipher())
  ->method(ENCRYPT_METHOD)
  ->secretkey(ENCRYPT_KEY)
  ->secretiv(ENCRYPT_IV);

// Sesión de usuario (común)
if (isset($_SESSION['signin']) && $_SESSION['signin'] === true) {
  $user_session = get_user_session_information(
    $connect,
    $_SESSION['user_id']
  );
} else {
  $user_session = null;
}

// Configuración del sitio
$config = new SiteConfig($connect);

// Mail
define('MAIL_NAME', $config->get("site_name"));
define('MAIL_HOST', $config->get("smtp_host"));
define('MAIL_EMAIL', $config->get("smtp_email"));
define('MAIL_PASSWORD', $config->get("smtp_password"));
define('MAIL_PORT', $config->get("smtp_port"));
define('MAIL_ENCRYPTION', $config->get("smtp_encryption"));

// Timezone
date_default_timezone_set(
  $config->get('site_timezone', 'America/Lima')
);

// Logger
$log = new Logger(BASE_DIR . '/storage/logs');
