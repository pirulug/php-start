<?php

// Config
require_once BASE_DIR . "/core/config/path.config.php";
require_once BASE_DIR . "/core/config/security.config.php";

// Libs
foreach (glob(BASE_DIR . '/core/libs/*.php') as $file) {
  require_once $file;
}

// Helpers
foreach (glob(BASE_DIR . '/core/helpers/*.php') as $file) {
  require_once $file;
}

// Middlewares
require_once BASE_DIR . "/core/middlewares/auth_admin.middleware.php";
require_once BASE_DIR . "/core/middlewares/auth_home.middleware.php";
require_once BASE_DIR . "/core/middlewares/permission.middleware.php";

// Routes
require_once BASE_DIR . "/core/routes/home.route.php";
require_once BASE_DIR . "/core/routes/admin.route.php";
require_once BASE_DIR . "/core/routes/api.route.php";
require_once BASE_DIR . "/core/routes/ajax.route.php";

// Global
// DataBase
$db = new DataBase()
  ->host(DB_HOST)
  ->name(DB_NAME)
  ->user(DB_USER)
  ->password(DB_PASS);

$connect = $db->getConnection();

// Notifier
$notifier = new Notifier();

// Cipher
$cipher = new Cipher()
  ->method(ENCRYPT_METHOD)
  ->secretkey(ENCRYPT_KEY)
  ->secretiv(ENCRYPT_IV);

// User Session
if (isset($_SESSION['signin']) && $_SESSION['signin'] === true) {
  $user_session = get_user_session_information($connect, $_SESSION['user_id']);
} else {
  $user_session = null;
}

// Options
$config = new SiteConfig($connect);

// Mail
define('MAIL_NAME', $config->get("site_name"));
define('MAIL_HOST', $config->get("smtp_host"));
define('MAIL_EMAIL', $config->get("smtp_email"));
define('MAIL_PASSWORD', $config->get("smtp_password"));
define('MAIL_PORT', $config->get("smtp_port"));
define('MAIL_ENCRYPTION', $config->get("smtp_encryption"));

// Timezone
date_default_timezone_set($config->get('site_timezone', 'America/Lima'));

// Logger
$log = new Logger(BASE_DIR . '/storage/logs');