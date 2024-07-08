<?php

// $user_session = get_user_session_information($connect);

$menuItems = [
  [
    'title' => 'Dashboard',
    'path'  => 'dashboard',
    'icon'  => 'sliders',
    'link'  => 'dashboard.php'
  ],
  [
    'title'     => 'Usuarios',
    'path'      => 'users',
    'icon'      => 'users',
    'collapsed' => true,
    'items'     => [
      [
        'title' => 'Nuevo Usuario',
        'path'  => 'user-add',
        'link'  => 'users/new.php'
      ],
      [
        'title' => 'Lista de usuario',
        'path'  => 'user-list',
        'link'  => 'users/list.php'
      ],
    ]
  ]
];
?>

<nav class="sidebar js-sidebar" id="sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="index.html">
      <span class="sidebar-brand-text align-middle">AdminPiru</span>
    </a>
    <ul class="sidebar-nav">
      <?php foreach ($menuItems as $item): ?>
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
                <li class="sidebar-item <?= $theme_path == $subItem['path'] ? "active" : "" ?>">
                  <a class="sidebar-link"
                    href="<?= APP_URL . "/admin/controllers/" . $subItem['link'] ?>"><?= $subItem['title'] ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php else: ?>
          <li class="sidebar-item <?= $theme_path == $item['path'] ? "active" : "" ?>">
            <a class="sidebar-link" href="<?= APP_URL . "/admin/controllers/" . $item['link'] ?>">
              <i class="align-middle" data-feather="<?= $item['icon'] ?>"></i>
              <span class="align-middle"><?= $item['title'] ?></span>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>

<div class="main">
  <!-- NavBar-->
  <nav class="navtop">
    <a class="sidebar-toggle js-sidebar-toggle">
      <i class="hamburger align-self-center"></i>
    </a>
    <div class="navbar-collapse collapse">
      <ul class="navbar-nav navbar-align">
        <li class="nav-item">
          <a class="nav-link" href="<?= APP_URL ?>">
            <i class="fa fa-eye"></i>
            Ver sitio
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="bd-theme" type="button" aria-expanded="false"
            data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <span class="theme-icon-active" id="bd-theme-icon">
              <i class="fa fa-sun"></i>
            </span>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
              <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="light"
                aria-pressed="false">
                <i class="fa fa-sun opacity-50 me-2"></i>
                Light
                <i class="pr-check fa fa-check ms-auto d-none"></i>
              </button>
            </li>
            <li>
              <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="dark"
                aria-pressed="false">
                <i class="fa fa-moon opacity-50 me-2"></i>
                Dark
                <i class="pr-check fa fa-check ms-auto d-none"></i>
              </button>
            </li>
            <li>
              <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="auto"
                aria-pressed="true">
                <i class="fa fa-circle-half-stroke opacity-50 me-2"></i>
                Auto
                <i class="pr-check fa fa-check ms-auto d-none"></i>
              </button>
            </li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
            <i class="align-middle" data-feather="user"></i>
          </a>
          <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
            <img class="avatar img-fluid rounded me-1" src="<?= getGravatar($user_session->user_email) ?>"
              alt="Charles Hall" />
            <span><?= $user_session->user_name ?></span>
          </a>
          <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="pages-profile.html">
              <i class="align-middle me-1" data-feather="user"></i>
              Profile
            </a>
            <a class="dropdown-item" href="#">
              <i class="align-middle me-1" data-feather="pie-chart"></i>
              Analytics
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="pages-settings.html">
              <i class="align-middle me-1" data-feather="settings"></i>
              Settings &amp; Privacy
            </a>
            <a class="dropdown-item" href="#">
              <i class="align-middle me-1" data-feather="help-circle"></i>
              Help Center
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= APP_URL ?>/admin/controllers/logout.php">
              <i class="align-middle me-1" data-feather="log-out"></i>
              Log out
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Content-->
  <div class="content">
    <div class="mb-3">
      <h1 class="h3 d-inline align-middle"><?= $theme_title ?? "" ?></h1>
    </div>