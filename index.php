<?php

require "core.php";

if ($_SERVER['REQUEST_URI'] == "/index.php") {
  header("Location: " . SITE_URL);
  exit();
}

// Obtener la ruta de la URL
$request = trim($_SERVER['REQUEST_URI'], '/');
$request = explode('?', $request)[0]; // Ignorar parámetros GET

$page_title       = $settings->st_sitename;
$page_description = $settings->st_description;
$page_keywords    = $settings->st_keywords;

$og_title       = $page_title;
$og_description = $page_description;
$og_image       = SITE_URL . "/assets/img/logo-vertical.png";
$og_url         = SITE_URL;

// Rutas disponibles
switch ($request) {
  case '':
    include 'controllers/index.php';
    break;

  case 'signin':
    include 'controllers/signin.php';
    break;

  case 'signup':
    include 'controllers/signup.php';
    break;

  case 'signout':
    include 'controllers/signout.php';
    break;

  case 'profile':
    include 'controllers/profile.php';
    break;

  case '404':
    include 'controllers/404.php';
    break;

  default:
    include 'controllers/404.php';
    break;
}
