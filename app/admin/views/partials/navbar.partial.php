<?php

$menu_dashboard = [
  'title' => 'Dashboard',
  'path'  => 'dashboard',
  'icon'  => 'sliders',
  'link'  => 'dashboard',
  'roles' => [1, 2]
];

$menu_users = [
  'title'     => 'Usuarios',
  'path'      => 'users',
  'icon'      => 'users',
  'collapsed' => true,
  'roles'     => [1, 2],
  'items'     => [
    [
      'title' => 'Nuevo Usuario',
      'path'  => 'user-new',
      'link'  => 'user/new',
      'roles' => [1, 2]
    ],
    [
      'title' => 'Lista de usuario',
      'path'  => 'user-list',
      'link'  => 'users',
      'roles' => [1, 2]
    ],
  ]
];

$menu_general = [
  'title'     => 'Configuración',
  'path'      => 'settings',
  'icon'      => 'settings',
  'collapsed' => true,
  'roles'     => [1],
  'items'     => [
    [
      'title' => 'General',
      'path'  => 'general',
      'link'  => 'settings/general',
      'roles' => [1]
    ],
    // [
    //   'title' => 'ADS',
    //   'path'  => 'ads',
    //   'link'  => 'settings/ads',
    //   'roles' => [1]
    // ],
    [
      'title' => 'SMTP',
      'path'  => 'smtp',
      'link'  => 'settings/smtp',
      'roles' => [1]
    ],
    [
      'title' => 'Brand',
      'path'  => 'brand',
      'link'  => 'settings/brand',
      'roles' => [1]
    ],
    [
      'title' => 'Información',
      'path'  => 'info',
      'link'  => 'settings/info',
      'roles' => [1]
    ],
    [
      'title' => 'Estadística',
      'path'  => 'statistics',
      'link'  => 'settings/statistics',
      'roles' => [1]
    ],
    [
      'title' => 'robots.txt',
      'path'  => 'robots',
      'link'  => 'settings/robots',
      'roles' => [1]
    ],
    [
      'title' => 'Sitemap.xml',
      'path'  => 'sitemap',
      'link'  => 'settings/sitemap',
      'roles' => [1]
    ],
  ]
];

$menuItems = [
  $menu_dashboard,
  $menu_users,
  $menu_general,
];

$role = $user_session->user_role ?? 0;
?>

<nav class="sidebar js-sidebar" id="sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="index.html">
      <span class="sidebar-brand-text align-middle"><?= SITE_NAME ?></span>
    </a>
    <ul class="sidebar-nav">
      <?php foreach ($menuItems as $item): ?>
        <?php if ($accessControl->hasAccess($item['roles'], $role)): // Verificar acceso al ítem principal ?>
          <?php if (isset($item['collapsed']) && $item['collapsed']): ?>

            <li class="sidebar-item <?= in_array($theme_path, array_column($item['items'], 'path')) ? 'active' : '' ?>">
              <a class="sidebar-link <?= in_array($theme_path, array_column($item['items'], 'path')) ? '' : 'collapsed' ?>"
                data-bs-target="#<?= $item['path'] ?>" data-bs-toggle="collapse">
                <i class="align-middle" data-feather="<?= $item['icon'] ?>"></i>
                <span class="align-middle"><?= $item['title'] ?></span>
              </a>
              <ul
                class="sidebar-dropdown list-unstyled collapse <?= in_array($theme_path, array_column($item['items'], 'path')) ? 'show' : '' ?>"
                id="<?= $item['path'] ?>" data-bs-parent="#sidebar">
                <?php foreach ($item['items'] as $subItem): ?>
                  <?php if ($accessControl->hasAccess($subItem['roles'], $role)): // Verificar acceso a los sub ítems ?>
                    <li class="sidebar-item <?= $theme_path == $subItem['path'] ? "active" : "" ?>">
                      <a class="sidebar-link" href="<?= SITE_URL_ADMIN . "/" . $subItem['link'] ?>"><?= $subItem['title'] ?></a>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php else: ?>
            <li class="sidebar-item <?= $theme_path == $item['path'] ? "active" : "" ?>">
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