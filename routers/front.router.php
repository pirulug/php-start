<?php
switch ($segments[0]) {
  case '':
    $template = [
      'title'  => 'Inicio',
      'path'   => 'home/index',
      'layout' => 'main',
      'auth'   => false,
    ];
    break;

  case 'signin':
    $template = [
      'title'  => 'Iniciar Sesión',
      'path'   => 'auth/signin',
      'layout' => 'main',
      'auth'   => false,
    ];
    break;

  case 'signup':
    $template = [
      'title'  => 'Registrarse',
      'path'   => 'auth/signup',
      'layout' => 'main',
      'auth'   => false,
    ];
    break;

  case 'signout':
    $template = [
      'title'  => 'Cerrar Sesión',
      'path'   => 'auth/signout',
      'layout' => 'main',
      'auth'   => false,
    ];
    break;

  default:
    $template = [
      'title'  => 'Página No Encontrada',
      'path'   => 'errors/404',
      'layout' => 'main',
      'auth'   => false,
    ];
    break;
}

// Validar acceso
if (!empty($template['auth']) && $template['auth']) {
  $accessManager->ensure_access($template['path'], $template['title']);
}

// Analytics
$analytics->trackVisit($template['title'], $_SERVER['REQUEST_URI']);

// Cargar archivos
include_once path_front($template['path']);
include_once path_front_layout($template['layout']);