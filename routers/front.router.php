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
      'title' => 'Cerrar Sesión',
      'path'  => 'auth/signout',
      'auth'  => false,
    ];
    break;

  case 'profile':
    $template = [
      'title'  => 'Perfil',
      'path'   => 'account/profile',
      'layout' => 'main',
      'auth'   => true,
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

// Cargar archivos
$result = path_front($template['path']);
if ($result['success']) {
  include_once $result['file'];
} else {
  echo "<div style='color:red; font-weight:bold;'>{$result['message']}</div>";
  exit();
}

if (!empty($template["layout"])) {
  include_once path_front_layout($template['layout']);
}