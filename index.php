<?php
// =============================================================
// 1. Cargar configuración e inicialización
// =============================================================
require_once __DIR__ . "/core/init.php";


// =============================================================
// 2. Evitar que el router procese archivos estáticos
// =============================================================

// Carpetas públicas (ajusta si cambias estructura)
$publicDirs = ['assets', 'uploads', 'images', 'img', 'css', 'js'];

// Ruta solicitada (por ejemplo: /assets/img/logo.png)
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath    = __DIR__ . $requestPath;

// --- Manejar favicon y robots.txt ---
if (preg_match('#/(favicon\.ico|robots\.txt)$#', $requestPath)) {
  if (file_exists($filePath)) {
    header("Content-Type: " . mime_content_type($filePath));
    readfile($filePath);
  } else {
    http_response_code(204); // No content (evita error 404)
  }
  exit;
}

// --- Servir archivos estáticos directamente ---
foreach ($publicDirs as $dir) {
  if (strpos($requestPath, "/{$dir}/") === 0 && file_exists($filePath)) {
    // Puedes agregar cache si deseas
    header("Cache-Control: public, max-age=604800"); // 7 días
    header("Content-Type: " . mime_content_type($filePath));
    readfile($filePath);
    exit;
  }
}


// =============================================================
// 3. Redirección si acceden directamente a /index.php
// =============================================================
if ($_SERVER['REQUEST_URI'] === "/index.php") {
  header("Location: " . SITE_URL);
  exit();
}


// =============================================================
// 4. Procesamiento de rutas amigables
// =============================================================
$request  = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $request);

// Router principal
if ($segments[0] === ADMIN_NAME) {
  require "routers/admin.router.php";
} elseif ($segments[0] === "api") {
  require "routers/api.router.php";
} elseif ($segments[0] === "ajax") {
  require "routers/ajax.router.php";
} else {
  require "routers/front.router.php";
}
