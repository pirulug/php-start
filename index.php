<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

define('BASE_DIR', __DIR__);

require_once BASE_DIR . "/config.php";
require_once BASE_DIR . "/core/config/path.config.php";
require_once BASE_DIR . "/core/config/security.config.php";

/*
|--------------------------------------------------------------------------
| Obtener URL limpia
|--------------------------------------------------------------------------
*/
$url          = isset($_GET['url']) ? trim($_GET['url'], '/') : '/';
$requestedUrl = trim($_GET['url'] ?? '', '/');

/*
|--------------------------------------------------------------------------
| Detectar contexto
|--------------------------------------------------------------------------
*/
$isAdmin = str_starts_with($requestedUrl, PATH_ADMIN);
$isApi   = str_starts_with($requestedUrl, PATH_API);
$isAjax  = str_starts_with($requestedUrl, PATH_AJAX);

/*
|--------------------------------------------------------------------------
| Cargar bootstrap según contexto
|--------------------------------------------------------------------------
*/
if ($isAdmin) {
  require_once BASE_DIR . "/core/bootstrap/admin.php";
} elseif ($isApi) {
  require_once BASE_DIR . "/core/bootstrap/api.php";
} elseif ($isAjax) {
  require_once BASE_DIR . "/core/bootstrap/ajax.php";
} else {
  require_once BASE_DIR . "/core/bootstrap/home.php";
}

/*
|--------------------------------------------------------------------------
| Resolver ruta
|--------------------------------------------------------------------------
*/
$route = Router::resolve($url);
$args  = $route['params'] ?? [];

/*
|--------------------------------------------------------------------------
| Ruta no encontrada
|--------------------------------------------------------------------------
*/
if (!$route) {

  http_response_code(404);

  // API / AJAX → JSON
  if ($isApi || $isAjax) {

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
      'status'  => 404,
      'success' => false,
      'code'    => 'NOT_FOUND',
      'message' => 'Recurso no encontrado',
      'path'    => '/' . $requestedUrl
    ]);

    exit;
  }

  // ADMIN → Vista de error
  if ($isAdmin) {

    require_once admin_action('errors.404');

    ob_start();
    require_once admin_view('errors.404');
    $content = ob_get_clean();

    require_once admin_layout('error');
    exit;
  }

  // FRONT
  echo 'Página no encontrada';
  exit;
}

/*
|--------------------------------------------------------------------------
| Analytics
|--------------------------------------------------------------------------
*/
if (!empty($route['analytics'])) {

  function get_api() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
      return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
      return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'] ?? null;
  }

  $pageTitle = $route['analytics']['title'];
  $pageUri   = $route['analytics']['uri']
    ?? ($_SERVER['REQUEST_URI'] ?? '/');
    
  $ip        = get_api() ?? "0.0.0.0";

  $log->info("Ip del cliente")
    ->file("analytics")
    ->with("Page Title", $pageTitle)
    ->with("Page URL", $pageUri)
    ->with("IP", $ip)
    ->write();

  $analytics = (new Analytics($connect))
    ->geoApiUrl('https://ipapi.pirulug.pw/api/v1/{ip}');

  $analytics->trackVisit($pageTitle, $pageUri, $ip);
}

/*
|--------------------------------------------------------------------------
| Ejecutar middlewares
|--------------------------------------------------------------------------
*/
foreach ($route['middlewares'] as [$middleware, $params]) {
  call_user_func(
    $middleware . '_middleware',
    $route,
    $params
  );
}

/*
|--------------------------------------------------------------------------
| Ejecutar action
|--------------------------------------------------------------------------
*/
if (!empty($route['action'])) {

  if ($isApi || $isAjax) {
    header('Content-Type: application/json; charset=utf-8');
  }

  require_once $route['action'];
}

/*
|--------------------------------------------------------------------------
| Renderizar vista + layout
|--------------------------------------------------------------------------
*/
if (!empty($route['view']) && !empty($route['layout'])) {

  ob_start();
  require_once $route['view'];
  $content = ob_get_clean();

  require_once $route['layout'];
}
