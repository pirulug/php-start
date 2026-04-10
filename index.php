<?php
header_remove('X-Powered-By');

if (file_exists(__DIR__ . '/MAINTENANCE')) {
  http_response_code(503);
  header('Retry-After: 300'); // 5 minutos
  exit('Página en mantenimiento. Volvemos en breve.');
}

define('APP_START', microtime(true));
define('BASE_DIR', __DIR__);

require_once BASE_DIR . "/config.php";
require_once BASE_DIR . "/core/config/path.config.php";
require_once BASE_DIR . "/core/config/security.config.php";

if (session_status() === PHP_SESSION_NONE) {
  session_name('__sid');
  session_start();
}

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

  // 1. RECURSOS ESTÁTICOS (Imágenes, CSS, JS, etc.)
  $staticExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'css', 'js', 'woff', 'woff2', 'ttf', 'mp4', 'pdf'];
  $extension        = strtolower(pathinfo($requestedUrl, PATHINFO_EXTENSION));

  if (in_array($extension, $staticExtensions)) {
    exit;
  }

  // 2. API / AJAX → JSON
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

  // 3. ADMIN → Vista de error
  if ($isAdmin) {
    require_once admin_action('errors.404');
    ob_start();
    require_once admin_view('errors.404');
    $content = ob_get_clean();
    require_once admin_layout('error');
    exit;
  }

  // 4. FRONT
  require_once home_action('errors.404');
  ob_start();
  require_once home_view('errors.404');
  $content = ob_get_clean();
  require_once home_layout(); // Por defecto usa 'main'
  exit;
}

/*
|--------------------------------------------------------------------------
| Analytics
|--------------------------------------------------------------------------
*/
if (!empty($route['analytics'])) {

  // Obtener IP real del cliente de forma segura (sin spoofing)
  function get_api() {
    return $_SERVER['REMOTE_ADDR'] ?? null;
  }

  $pageTitle = $route['analytics']['title'];
  $pageUri   = $route['analytics']['uri']
    ?? ($_SERVER['REQUEST_URI'] ?? '/');

  $ip = get_api() ?? "0.0.0.0";

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
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
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

$log->info(round((microtime(true) - APP_START) * 1000, 2) . ' ms')
  ->file("index")
  ->with("URL", $url)
  ->write();