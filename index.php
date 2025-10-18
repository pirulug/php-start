<?php

require_once "core/init.php";

// Redirección si acceden a /index.php
if ($_SERVER['REQUEST_URI'] === "/index.php") {
  header("Location: " . SITE_URL);
  exit();
}

// Obtener la ruta de la URL
$request  = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $request);

if ($segments[0] === ADMIN_NAME) {
  require "routers/admin.router.php";
} elseif ($segments[0] === "api") {
  require "routers/api.router.php";
} elseif ($segments[0] === "ajax") {
  require "routers/ajax.router.php";
} else {
  require "routers/front.router.php";
}