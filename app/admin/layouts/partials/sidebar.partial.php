<?php

$menu_dashboard = [
  'title' => 'Dashboard',
  'path'  => 'dashboard.dashboard',
  'icon'  => 'sliders',
  'link'  => 'dashboard'
];

$menu_users = [
  'title'     => 'Usuarios',
  'path'      => 'users.list',
  'icon'      => 'users',
  'collapsed' => true,
  'items'     => [
    [
      'title' => 'Nuevo Usuario',
      'path'  => 'users.new',
      'link'  => 'user/new',
    ],
    [
      'title' => 'Lista de usuario',
      'path'  => 'users.list',
      'link'  => 'users',
    ],
  ]
];

$menu_roles = [
  'title'     => 'Administrar Roles',
  'path'      => 'roles.list',
  'icon'      => 'lock',
  'collapsed' => true,
  'items'     => [
    [
      'title' => 'Nuevo rol',
      'path'  => 'roles.new',
      'link'  => 'rol/new'
    ],
    [
      'title' => 'Lista roles',
      'path'  => 'roles.list',
      'link'  => 'roles'
    ],
  ]
];

$menu_permissions = [
  'title'     => 'Administrar Permisos',
  'path'      => 'permissions',
  'icon'      => 'key',
  'collapsed' => true,
  'items'     => [
    [
      'title' => 'Nuevo permiso',
      'path'  => 'permissions.new',
      'link'  => 'permission/new',
    ],
    [
      'title' => 'Lista permiso',
      'path'  => 'permissions.list',
      'link'  => 'permissions',
    ],
  ]
];

$menu_general = [
  'title'     => 'Configuración',
  'path'      => 'settings',
  'icon'      => 'settings',
  'collapsed' => true,
  'items'     => [
    [
      'title' => 'General',
      'path'  => 'settings.general',
      'link'  => 'settings/general'
    ],
    // [
    //   'title' => 'ADS',
    //   'path'  => 'ads',
    //   'link'  => 'settings/ads'
    // ],
    [
      'title' => 'SMTP',
      'path'  => 'settings.smtp',
      'link'  => 'settings/smtp'
    ],
    [
      'title' => 'Brand',
      'path'  => 'settings.brand',
      'link'  => 'settings/brand'
    ],
    [
      'title' => 'Información',
      'path'  => 'settings.info',
      'link'  => 'settings/info'
    ],
    // [
    //   'title' => 'Estadística',
    //   'path'  => 'statistics',
    //   'link'  => 'settings/statistics'
    // ],
    [
      'title' => 'robots.txt',
      'path'  => 'settings.robots',
      'link'  => 'settings/robots'
    ],
    [
      'title' => 'Sitemap.xml',
      'path'  => 'settings.sitemap',
      'link'  => 'settings/sitemap'
    ],
  ]
];

$menuItems = [
  $menu_dashboard,
  $menu_users,
  $menu_roles,
  $menu_permissions,
  $menu_general,
];

$role = $user_session->role_id ?? 0;
?>

<nav class="sidebar js-sidebar" id="sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="index.html">
      <span class="sidebar-brand-text align-middle"><?= SITE_NAME ?></span>
    </a>
    <ul class="sidebar-nav">
      <?php foreach ($menuItems as $item): ?>
        <?php if ($accessManager->can_access($item['path'])): // Verificar acceso al ítem principal ?>
          <?php if (isset($item['collapsed']) && $item['collapsed']): ?>

            <li class="sidebar-item <?= in_array($template['path'], array_column($item['items'], 'path')) ? 'active' : '' ?>">
              <a class="sidebar-link <?= in_array($template['path'], array_column($item['items'], 'path')) ? '' : 'collapsed' ?>"
                data-bs-target="#<?= $item['path'] ?>" data-bs-toggle="collapse">
                <i class="align-middle" data-feather="<?= $item['icon'] ?>"></i>
                <span class="align-middle"><?= $item['title'] ?></span>
              </a>
              <ul
                class="sidebar-dropdown list-unstyled collapse <?= in_array($template['path'], array_column($item['items'], 'path')) ? 'show' : '' ?>"
                id="<?= $item['path'] ?>" data-bs-parent="#sidebar">
                <?php foreach ($item['items'] as $subItem): ?>
                  <?php if ($accessManager->can_access($subItem['path'])): // Verificar acceso a los sub ítems ?>
                    <li class="sidebar-item <?= $template['path'] == $subItem['path'] ? "active" : "" ?>">
                      <a class="sidebar-link" href="<?= SITE_URL_ADMIN . "/" . $subItem['link'] ?>"><?= $subItem['title'] ?></a>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php else: ?>
            <li class="sidebar-item <?= $template['path'] == $item['path'] ? "active" : "" ?>">
              <a class="sidebar-link" href="<?= SITE_URL_ADMIN . "/" . $item['link'] ?>">
                <i class="align-middle" data-feather="<?= $item['icon'] ?>"></i>
                <span class="align-middle"><?= $item['title'] ?></span>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>