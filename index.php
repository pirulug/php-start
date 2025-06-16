<?php

require "core.php";

if ($_SERVER['REQUEST_URI'] == "/index.php") {
  header("Location: " . SITE_URL);
  exit();
}

// Obtener la ruta de la URL
$request  = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $request);

switch ($segments[0]) {
  case '':
    $visitCounter->register_visit("Home");
    include 'pages/index.php';
    break;

  case 'signin':
    $visitCounter->register_visit("Sign in");
    include 'pages/signin.php';
    break;

  case 'signup':
    $visitCounter->register_visit("Sign up");
    include 'pages/signup.php';
    break;

  case 'signout':
    $visitCounter->register_visit("Sign out");
    include 'pages/signout.php';
    break;

  case 'profile':
    $accessControl->check_access([1, 2, 3], SITE_URL);
    $visitCounter->register_visit("Profile");
    include 'pages/profile.php';
    break;

  case '404':
    $visitCounter->register_visit("404");
    include 'pages/404.php';
    break;

  default:
    $visitCounter->register_visit("404");
    include 'pages/404.php';
    break;
}