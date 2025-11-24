<?php

// =============================================================
// 0. Configuración 
// =============================================================
if (!file_exists(__DIR__ . "/config.php")) {
  die("Te falta el archivo config.php");
}

require_once __DIR__ . "/config.php";

// =============================================================
// 1. Cargar configuración e inicialización
// =============================================================
require_once __DIR__ . "/core/init.php";


// =============================================================
// 2. Redirección si acceden directamente a /index.php
// =============================================================
if ($_SERVER['REQUEST_URI'] === "/index.php") {
  header("Location: " . SITE_URL);
  exit();
}


// =============================================================
// 3. Procesamiento de rutas amigables
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
