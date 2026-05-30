<?php

// Boot Loadning
require_once BASE_DIR . "/core/boot/core.php";
require_once BASE_DIR . "/core/boot/blocks.php";
require_once BASE_DIR . "/core/boot/load.php";
require_once BASE_DIR . "/core/boot/url.php";
require_once BASE_DIR . "/core/boot/get.php";
require_once BASE_DIR . "/core/boot/is.php";
require_once BASE_DIR . "/core/boot/has.php";
require_once BASE_DIR . "/core/boot/format.php";
require_once BASE_DIR . "/core/boot/meta.php";
require_once BASE_DIR . "/core/boot/seo.php";
require_once BASE_DIR . "/core/boot/lang.php";

// Libs
load_core_files("libraries");

// Components (UI)
load_core_files("components");

// Helpers
load_core_files("helpers");

// Middlewares
load_core_files("middleware");

// Configuracion de la base de datos
$db = (new DataBase())
  ->host(DB_HOST)
  ->name(DB_NAME)
  ->user(DB_USER)
  ->password(DB_PASS);

$connect = $db->getConnection();

// Logger (Recibe la ruta física de los logs)
$log = new Logger(BASE_DIR . "/storage/logs");

// Notifier
$notifier = new Notifier();

// Cipher
$cipher = (new Cipher())
  ->method(ENCRYPT_METHOD)
  ->secretkey(ENCRYPT_KEY)
  ->secretiv(ENCRYPT_IV);

// Configuración del sitio
$config = new SiteConfig($connect);

// Fecha y Hora
$site_date = new SiteDate($config);

// Sona Horaria
date_default_timezone_set(
  $config->get("site_timezone", "America/Lima")
);

// -----------------------------------------------------------------------------
// SECCIÓN: TRAZABILIDAD Y REGISTRO DE ACCESOS (LOGGING DE RUTA)
// -----------------------------------------------------------------------------
// Ignorar peticiones de assets estáticos y endpoints AJAX recurrentes para no saturar los logs
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$is_asset = preg_match('/\.(js|css|png|jpg|jpeg|gif|webp|svg|ico|woff2?|ttf)$/i', $request_uri);
if (!$is_asset && PHP_SAPI !== 'cli') {
  $user_id = $_SESSION['user_id'] ?? null;
  $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';
  
  if ($user_id) {
    // Usuario autenticado: trazar por su ID
    $log->info("Acceso de usuario logueado")
      ->with("user_id", $user_id)
      ->with("ip", $ip_address)
      ->write();
  } else {
    // Usuario no autenticado: trazar por IP
    $log->info("Acceso anonimo")
      ->with("ip", $ip_address)
      ->write();
  }
}


// Cargar idioma global
load_textdomain('default', BASE_DIR . "/core/languages/" . get_locale() . ".php");

// Sesión de usuario (común)
if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  $user_session = get_user_session($connect, $_SESSION["user_id"]);

  // Seguridad: Si el usuario está en sesión pero no se pudo cargar (ej: desactivado o borrado)
  if (!$user_session) {
    session_unset();
    session_destroy();
    $user_session = null;
  }
} else {
  $user_session = null;
}

// Cargar services
load_routes_services();