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
    include 'pages/index.php';
    break;

  case 'signin':
    include 'pages/signin.php';
    break;

  case 'signup':
    include 'pages/signup.php';
    break;

  case 'signout':
    include 'pages/signout.php';
    break;

  case 'profile':
    $accessControl->check_access([1, 2, 3], SITE_URL);
    include 'pages/profile.php';
    break;

  case '404':
    include 'pages/404.php';
    break;

  default:
    include 'pages/404.php';
    break;
}