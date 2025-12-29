<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

const BASE_DIR = __DIR__;

require_once BASE_DIR . "/config.php";
require_once BASE_DIR . "/core/bootstrap/init.php";

/*
|--------------------------------------------------------------------------
| Obtener URL limpia
|--------------------------------------------------------------------------
*/
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '/';

/*
|--------------------------------------------------------------------------
| Resolver ruta
|--------------------------------------------------------------------------
*/
$route = Router::resolve($url);

$requestedUrl = trim($_GET['url'] ?? '', '/');

$isAdmin = str_starts_with($requestedUrl, PATH_ADMIN);
$isApi   = str_starts_with($requestedUrl, PATH_API);
$isAjax  = str_starts_with($requestedUrl, PATH_AJAX);


if (!$route) {

  http_response_code(404);

  /*
  |--------------------------------------------------------------------------
  | API / AJAX → JSON
  |--------------------------------------------------------------------------
  */
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

  /*
  |--------------------------------------------------------------------------
  | ADMIN → HTML personalizado
  |--------------------------------------------------------------------------
  */
  if ($isAdmin) {

    require_once admin_action('errors.404');

    ob_start();
    require_once admin_view('errors.404');
    $content = ob_get_clean();

    require_once admin_layout('error');
    exit;
  }

  /*
  |--------------------------------------------------------------------------
  | FRONT (fallback)
  |--------------------------------------------------------------------------
  */
  echo 'Página no encontrada';
  exit;
}

if (!empty($route['analytics'])) {

  $analytics = new Analytics($connect);

  $pageTitle = $route['analytics']['title'];
  $pageUri   = $route['analytics']['uri']
    ?? ($_SERVER['REQUEST_URI'] ?? '/');

  $analytics->trackVisit($pageTitle, $pageUri);
}

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
    require_once $route['action'];
  }else{
    require_once $route['action'];
  }
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


