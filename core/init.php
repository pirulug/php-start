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

// Conexión a base de datos
$db      = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$connect = $db->getConnection();

if (!$connect) {
  die("Error de conexión a la base de datos");
}

// Notificador
$notifier = new Notifier();

// Cifrador
$cipher = new Cipher(ENCRYPT_METHOD, ENCRYPT_KEY, ENCRYPT_IV);

// Obtener Información de usuario
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $user_session = get_user_session_information($connect, $_SESSION["user_id"]);
} else {
  $user_session = null;
}

// Gestor de accesos
$accessManager = new AccessManager($connect, $user_session);

// Analytics
// $analytics = new Analytics($connect);

// Configuraciones del sitio
$config  = new SiteConfig($connect);
$favicon = json_decode($config->get("favicon"), true);

/**
 * Default Timezone
 */
date_default_timezone_set($config->get('site_timezone', 'UTC'));

file_put_contents(BASE_DIR . "/logs/debug_mail.log", date("Y-m-d H:i:s") . " - Creando MailService en init.php\n", FILE_APPEND);


// Mail Service
$mailService = (new MailService())
  ->host($config->get("smtp_host"))
  ->email($config->get("smtp_email"))
  ->password($config->get("smtp_password"))
  ->port($config->get("smtp_port"))
  ->encryption($config->get("smtp_encryption"))
  ->init();

// $send = $mailService
//   ->to("pirulug@gmail.com")
//   ->subject("Correo de prueba")
//   ->body("<p>Este es un correo de prueba desde PHP-Start.</p>")
//   ->send();

// echo $send["message"];

// exit();
