<?php

if (isset($_SESSION["user_name"])) {
  $check_access = check_access($connect);

  if ($check_access['user_role'] == 1 || $check_access['user_role'] == 0) {
    $menuItems = [
      [
        'title' => 'Dashboard',
        'link'  => APP_URL . "/admin/controllers/dashboard.php"
      ],
      [
        'title'    => $user_session->user_name,
        'link'     => '#',
        'dropdown' => true,
        'items'    => [
          [
            'title' => 'Perfil',
            'link'  => 'profile.php'
          ],
          [
            'title' => 'Favoritos',
            'link'  => 'favorites.php'
          ],
          ['divider' => true],
          [
            'title' => 'Salir',
            'link'  => 'signout.php'
          ],
        ]
      ]
    ];
  } else {
    $menuItems = [
      [
        'title'    => $user_session->user_name,
        'link'     => '#',
        'dropdown' => true,
        'items'    => [
          [
            'title' => 'Perfil',
            'link'  => 'profile.php'
          ],
          [
            'title' => 'Favoritos',
            'link'  => 'favorites.php'
          ],
          ['divider' => true],
          [
            'title' => 'Salir',
            'link'  => 'signout.php'
          ],
        ]
      ]
    ];
  }
} else {
  $menuItems = [
    ['title' => 'Help', 'link' => '/'],
    ['title' => 'Blog', 'link' => '/'],
    [
      'title'    => 'Auth', 'link' => '#', 'dropdown' => true, 'items' => [
        ['title' => 'Login', 'link' => 'signin.php'],
        ['title' => 'Register', 'link' => 'signup.php'],
        ['title' => 'Salir', 'link' => 'signout.php'],
        // ['divider' => true]
      ]
    ]
  ];
}





?>

<div class="navbar navbar-expand-lg fixed-top bg-body">
  <div class="container">
    <a class="navbar-brand" href="/"><?= APP_NAME ?></a>
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
          <?php if (isset($item['dropdown']) && $item['dropdown']): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="<?= $item['link'] ?>" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <?= $item['title'] ?>
              </a>
              <ul class="dropdown-menu">
                <?php foreach ($item['items'] as $subItem): ?>
                  <?php if (isset($subItem['divider']) && $subItem['divider']): ?>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                  <?php else: ?>
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
        <?php endforeach; ?>
      </ul>
    </div>
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button"
          aria-expanded="false" data-bs-toggle="dropdown">
          <span class="theme-icon-active mr-1" id="bd-theme-icon">
            <i class="bi bi-sun"></i>
          </span>
          <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="light"
              aria-pressed="false">
              <i class="bi bi-sun opacity-50 me-2"></i>
              Light
              <i class="pr-check bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="dark"
              aria-pressed="false">
              <i class="bi bi-moon opacity-50 me-2"></i>
              Dark
              <i class="pr-check bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="auto"
              aria-pressed="true">
              <i class="bi bi-circle-half opacity-50 me-2"></i>
              Auto
              <i class="pr-check bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</div>