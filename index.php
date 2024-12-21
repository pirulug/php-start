<?php

require "core.php";

if ($_SERVER['REQUEST_URI'] == "/index.php") {
  header("Location: " . SITE_URL);
  exit();
}

// Obtener la ruta de la URL
$request = trim($_SERVER['REQUEST_URI'], '/');
$request = explode('?', $request)[0];



switch ($request) {
  case '':
    extract(setPageMetaData());
    include 'controllers/index.php';
    break;

  case 'signin':
    extract(setPageMetaData("Iniciar Sesion", $request));
    include 'controllers/signin.php';
    break;

  case 'signup':
    extract(setPageMetaData("Registrate", $request));
    include 'controllers/signup.php';
    break;

  case 'signout':
    extract(setPageMetaData("Salir", $request));
    include 'controllers/signout.php';
    break;

  case 'profile':
    extract(setPageMetaData("Perfil", $request));
    include 'controllers/profile.php';
    break;

  case '404':
    extract(setPageMetaData("404", $request));
    include 'controllers/404.php';
    break;

  default:
    extract(setPageMetaData("404", $request));
    include 'controllers/404.php';
    break;
}