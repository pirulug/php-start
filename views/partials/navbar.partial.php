<?php

$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

if (isset($_SESSION["user_name"])) {
  $check_access = check_access($connect);

  $menuItems = [
    [
      'title' => 'Dashboard',
      'link'  => SITE_URL . "/admin/controllers/dashboard.php",
      'roles' => [1, 2]
    ],
    [
      'title' => 'Admin',
      'link'  => SITE_URL . "/admin",
      'roles' => [1, 2, 3]
    ],
    [
      'title'    => $user_session->user_name,
      'link'     => '#',
      'dropdown' => true,
      'roles'    => [1, 2, 3],
      'items'    => [
        [
          'title' => 'Perfil',
          'link'  => 'profile.php',
          'roles' => [1, 2, 3]
        ],
        [
          'title' => 'Favoritos',
          'link'  => 'favorites.php',
          'roles' => [1, 2]
        ],
        ['divider' => true],
        [
          'title' => 'Salir',
          'link'  => 'signout.php',
          'roles' => [1, 2, 3]
        ],
      ]
    ]
  ];
} else {
  $menuItems = [
    ['title' => 'Admin', 'link' => SITE_URL . '/admin', 'roles' => []],
    [
      'title'    => 'Auth',
      'link'     => '#',
      'dropdown' => true,
      'roles'    => [],
      'items'    => [
        ['title' => 'Login', 'link' => 'signin.php', 'roles' => []],
        ['title' => 'Register', 'link' => 'signup.php', 'roles' => []],
      ]
    ]
  ];
}

?>

<nav class="navbar navbar-expand-lg bg-body">
  <div class="container">
    <a class="navbar-brand" href="<?= SITE_NAME ?>">
      <img id="logo-ligth" src="<?= $brand->st_whitelogo ?? "https://dummyimage.com/320x71/000/fff.jpg" ?>"
        alt="Logo Light" class="logo-light" height="40">
      <img id="logo-dark" src="<?= $brand->st_darklogo ?? "https://dummyimage.com/320x71/fff/000.jpg" ?>"
        alt="Logo Dark" class="logo-dark d-none" height="40">
    </a>
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
      aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ms-md-auto">
        <li class="nav-item">
          <a class="nav-link" href="/">Inicio</a>
        </li>
        <?php foreach ($menuItems as $item): ?>
          <?php
          // Verificar si el ítem tiene roles definidos y si el usuario tiene acceso
          if (!isset($item['roles']) || empty($item['roles']) || $accessControl->hasAccess($item['roles'], $role)):
            ?>
            <?php if (isset($item['dropdown']) && $item['dropdown']): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="<?= $item['link'] ?>" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <?= $item['title'] ?>
                </a>
                <ul class="dropdown-menu">
                  <?php foreach ($item['items'] as $subItem): ?>
                    <?php
                    // Verificar si el sub ítem tiene roles definidos y si el usuario tiene acceso
                    if (isset($subItem['divider']) && $subItem['divider']): ?>
                      <li>
                        <hr class="dropdown-divider">
                      </li>
                    <?php elseif (!isset($subItem['roles']) || empty($subItem['roles']) || $accessControl->hasAccess($subItem['roles'], $role)): ?>
                      <li><a class="dropdown-item" href="<?= $subItem['link'] ?>"><?= $subItem['title'] ?></a></li>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= $item['link'] ?>"><?= $item['title'] ?></a>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </div>
    <ul class="navbar-nav">
      <button class="nav-link py-2 d-flex align-items-center" id="bd-theme-toggle" type="button"><span
          class="theme-icon-active mr-1" id="bd-theme-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="feather feather-sun">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
          </svg>
        </span>
      </button>
    </ul>
  </div>
</nav>

<main class="flex-shrink-0">

  <?php if ($messageHandler->hasMessages()): ?>
    <div class="container ">
      <?= $messageHandler->displayMessages(); ?>
    </div>
  <?php endif; ?>