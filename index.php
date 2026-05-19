<?php

// PHP-Start

// VERSION CHECK
if (version_compare(PHP_VERSION, '8.4.0', '<')) {
  http_response_code(500);
  exit("Error: Su versión de PHP (" . PHP_VERSION . ") no es compatible con este sistema. Se requiere PHP 8.4 o superior.");
}

// Initial setup
const BASE_DIR = __DIR__;

// MAINTENANCE MODE
if (file_exists(BASE_DIR . '/MAINTENANCE')) {
  http_response_code(503);
  header('Retry-After: 300');
  exit('Página en mantenimiento. Volvemos en breve.');
}

// Cargar las configuraciones
require_once BASE_DIR . "/config.php";
require_once BASE_DIR . "/core/configs/cache.config.php";
require_once BASE_DIR . "/core/configs/path.config.php";
require_once BASE_DIR . "/core/configs/security.config.php";
require_once BASE_DIR . "/core/configs/app.config.php";

// Session Management
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// URL CLEANING
$requested_url = trim($_GET["url"] ?? "", "/");
$requested_url = $requested_url ?: "/";

// CONTEXT DETECTION
$url_parts    = explode('/', $requested_url);
$first_part   = $url_parts[0] ?? '';
$is_ctx_admin = ($first_part === PATH_ADMIN);
$is_ctx_api   = ($first_part === PATH_API);

// BOOTSTRAP LOADING
require_once BASE_DIR . "/core/bootstraps/main.bootstrap.php";

// --------------------------------------------------------------------------
// MODULAR PROTECTION (ENABLE/DISABLE MODULES)
// --------------------------------------------------------------------------

// Bloqueo de API si está desactivada
if ($is_ctx_api && !has_api()) {
  http_response_code(404);
  header('Content-Type: application/json; charset=utf-8');
  exit(json_encode([
    'status'  => 404,
    'success' => false,
    'code'    => 'API_DISABLED',
    'message' => 'La API se encuentra deshabilitada en este proyecto.'
  ]));
}

// Bloqueo de Frontend si está desactivado (Redirigir al Admin Login)
if (!$is_ctx_admin && !$is_ctx_api && !has_front()) {
  header("Location: " . admin_route("login"));
  exit();
}

if ($is_ctx_admin) {
  require_once BASE_DIR . "/core/bootstraps/admin.bootstrap.php";
} elseif ($is_ctx_api) {
  require_once BASE_DIR . "/core/bootstraps/api.bootstrap.php";
} else {
  require_once BASE_DIR . "/core/bootstraps/front.bootstrap.php";
}

// Cargar rutas
if ($is_ctx_admin) {
  load_routes_admin();
} elseif ($is_ctx_api) {
  load_routes_api();
} else {
  load_routes_front();
}

// ROUTE RESOLUTION
$route = Router::resolve($requested_url);
$args  = $route["params"] ?? [];

// var_dump($route);

// ERROR 404
if (!$route) {
  http_response_code(404);

  // Fallback para archivos estáticos faltantes (Evita bucles pesados)
  $staticExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'css', 'js', 'woff', 'woff2', 'ttf', 'mp4', 'pdf'];
  $extension        = strtolower(pathinfo($requested_url, PATHINFO_EXTENSION));

  if (in_array($extension, $staticExtensions)) {
    exit();
  }

  if ($is_ctx_api) {
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode([
      'status'  => 404,
      'success' => false,
      'code'    => 'NOT_FOUND',
      'message' => 'Recurso no encontrado',
      'path'    => '/' . $requested_url
    ]));
  } elseif ($is_ctx_admin) {
    $route = Router::route('404')
      ->setContext(CTX_ADMIN)
      ->action('errors@404')
      ->view('errors@404')
      ->layout('error')
      ->getRoute();
  } else {
    $route = Router::route('404')
      ->setContext(CTX_FRONT)
      ->action('errors@404')
      ->view('errors@404')
      ->layout('error')
      ->getRoute();
  }
}

// --------------------------------------------------------------------------
// EJECUCIÓN DE LA RUTA
// --------------------------------------------------------------------------

// MIDDLEWARES (Por implementar sistema de carga)
foreach ($route['middlewares'] as [$middleware, $params]) {
  call_user_func($middleware . '_middleware', $route, $params);
}

// ACTION EXECUTION
if (!empty($route['action'])) {
  require_once $route['action'];
}

// VIEW EXECUTION
if (!empty($route['view'])) {
  ob_start();
  require_once $route['view'];
  $content = ob_get_clean();

  // LAYOUT EXECUTION
  if (!empty($route['layout'])) {
    require_once $route['layout'];
  } else {
    echo $content;
  }
}