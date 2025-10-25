<?php

$routes = [

  // Dashboard
  'dashboard'   => [
    'title'  => 'Dashboard',
    'path'   => 'dashboard-dashboard',
    'layout' => 'main',
    'auth'   => true,
    'icon'   => 'sliders',
    'link'   => 'dashboard',
  ],

  // Usuarios
  'users'       => [
    'title'     => 'Usuarios',
    'path'      => 'users-list',
    'layout'    => 'main',
    'auth'      => true,
    'icon'      => 'users',
    'collapsed' => true,
    'items'     => [
      'user/new'    => [
        'title'  => 'Nuevo Usuario',
        'path'   => 'users-new',
        'layout' => 'main',
        'auth'   => true,
      ],
      'users'       => [
        'title'  => 'Lista de Usuarios',
        'path'   => 'users-list',
        'layout' => 'main',
        'auth'   => true,
      ],
      // 👇 rutas ocultas (no aparecen en sidebar)
      'user/edit'   => [
        'title'  => 'Editar Usuario',
        'path'   => 'users-edit',
        'layout' => 'main',
        'auth'   => true,
        'hidden' => true,
      ],
      'user/delete' => [
        'title'  => 'Eliminar Usuario',
        'path'   => 'users-delete',
        'auth'   => true,
        'hidden' => true,
      ],
    ],
  ],

  // Roles
  'roles'       => [
    'title'     => 'Roles',
    'path'      => 'roles-list',
    'layout'    => 'main',
    'auth'      => true,
    'icon'      => 'lock',
    'collapsed' => true,
    'items'     => [
      'rol/new'    => [
        'title'  => 'Nuevo Rol',
        'path'   => 'roles-new',
        'layout' => 'main',
        'auth'   => true,
      ],
      'roles'      => [
        'title'  => 'Lista Roles',
        'path'   => 'roles-list',
        'layout' => 'main',
        'auth'   => true,
      ],
      'rol/edit'   => [
        'title'  => 'Editar Rol',
        'path'   => 'roles-edit',
        'layout' => 'main',
        'auth'   => true,
        'hidden' => true,
      ],
      'rol/delete' => [
        'title'  => 'Eliminar Rol',
        'path'   => 'roles-delete',
        'auth'   => true,
        'hidden' => true,
      ],
    ],
  ],

  // Permisos
  'permissions' => [
    'title'     => 'Permisos',
    'path'      => 'permissions-list',
    'layout'    => 'main',
    'auth'      => true,
    'icon'      => 'key',
    'collapsed' => true,
    'items'     => [
      'permission/new'    => [
        'title'  => 'Nuevo Permiso',
        'path'   => 'permissions-new',
        'layout' => 'main',
        'auth'   => true,
      ],
      'permissions'       => [
        'title'  => 'Lista Permisos',
        'path'   => 'permissions-list',
        'layout' => 'main',
        'auth'   => true,
      ],
      'permission/edit'   => [
        'title'  => 'Editar Permiso',
        'path'   => 'permissions-edit',
        'layout' => 'main',
        'auth'   => true,
        'hidden' => true,
      ],
      'permission/delete' => [
        'title'  => 'Eliminar Permiso',
        'path'   => 'permissions-delete',
        'auth'   => true,
        'hidden' => true,
      ],
    ],
  ],

  // Configuración
  'settings'    => [
    'title'     => 'Configuración',
    'path'      => 'settings-general',
    'layout'    => 'main',
    'auth'      => true,
    'icon'      => 'settings',
    'collapsed' => true,
    'items'     => [
      'settings/general' => [
        'title'  => 'General',
        'path'   => 'settings-general',
        'layout' => 'main',
        'auth'   => true,
      ],
      'settings/smtp'    => [
        'title'  => 'SMTP',
        'path'   => 'settings-smtp',
        'layout' => 'main',
        'auth'   => true,
      ],
      'settings/brand'   => [
        'title'  => 'Marca',
        'path'   => 'settings-brand',
        'layout' => 'main',
        'auth'   => true,
      ],
      'settings/info'    => [
        'title'  => 'Información',
        'path'   => 'settings-info',
        'layout' => 'main',
        'auth'   => true,
      ],
      'settings/robots'  => [
        'title'  => 'robots.txt',
        'path'   => 'settings-robots',
        'layout' => 'main',
        'auth'   => true,
      ],
      'settings/sitemap' => [
        'title'  => 'Sitemap.xml',
        'path'   => 'settings-sitemap',
        'layout' => 'main',
        'auth'   => true,
      ],
    ],
  ],

  // Login
  'login'       => [
    'title'  => 'Login',
    'path'   => 'auth-login',
    'layout' => 'auth',
    'auth'   => false,
  ],

  // Logout
  'logout'      => [
    'title' => 'Logout',
    'path'  => 'auth-logout',
    'auth'  => false,
  ],

  // Account 
  "account"     => [
    'title'  => 'Cuenta',
    'layout' => 'main',
    'auth'   => true,
    'items'  => [
      'account/profile'  => [
        'title'  => 'Mi Perfil',
        'path'   => 'account-profile',
        'layout' => 'main',
        'auth'   => true,
      ],
      'account/settings' => [
        'title'  => 'Ajustes de Cuenta',
        'path'   => 'account-settings',
        'layout' => 'main',
        'auth'   => true,
      ],
    ],
  ],

  // test
  'test'        => [
    'title'  => 'Test',
    'path'   => 'test-index',
    'auth'   => true,
    'layout' => 'main',
  ],
  
  'iplo'        => [
    'title'  => 'Test',
    'path'   => 'test-ip',
    'auth'   => true,
    'layout' => 'main',
  ],

];


$routeKey = $segments[1] ?? 'login';
$subKey   = isset($segments[2]) ? "$routeKey/{$segments[2]}" : $routeKey;
$template = null;
$id       = $segments[3] ?? null;

// 1 Buscar en el primer nivel
if (isset($routes[$routeKey])) {
  $route = $routes[$routeKey];

  // 2 Buscar dentro de sus items (subrutas)
  if (isset($route['items'][$subKey])) {
    $template = $route['items'][$subKey];
  } else {
    $template = $route;
  }
}

// 3 Si no se encontró, buscar en items de otros módulos (ocultas)
if (!$template) {
  foreach ($routes as $group) {
    if (!isset($group['items']))
      continue;
    if (isset($group['items'][$subKey])) {
      $template = $group['items'][$subKey];
      break;
    }
  }
}

// 4 Error si no existe
if (!$template) {
  $template = [
    'title'  => '404',
    'path'   => 'errors-404-alt',
    'layout' => 'main',
  ];
  http_response_code(404);
  include_once path_admin($template['path']);
  include_once path_admin_layout($template['layout']);

  exit();
}

// 5 Validar acceso
if (!empty($template['auth']) && $template['auth']) {
  $accessManager->ensure_access($template['path'], $template['title']);
}

// 6 Cargar archivos
include_once path_admin($template['path']);

if (isset($template['layout'])) {
  include_once path_admin_layout($template['layout']);
}