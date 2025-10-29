<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title><?= $template['title'] ?> | <?= SITE_NAME ?></title>

  <link rel="apple-touch-icon" sizes="180x180" href="<?= site_favicon($favicon["apple-touch-icon"]) ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= site_favicon($favicon["favicon-32x32"]) ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= site_favicon($favicon["favicon-16x16"]) ?>">
  <link rel="manifest" href="<?= site_favicon($favicon["webmanifest"]) ?>">

  <link rel="stylesheet" href="<?= SITE_URL ?>/static/assets/css/bootstrapicons.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/static/assets/css/fontawesome.css">

  <link rel="stylesheet" href="<?= SITE_URL ?>/static/assets/css/piruui.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/static/assets/css/sticky.css">

  <link rel="stylesheet" href="<?= SITE_URL ?>/static/plugins/toastifyjs/toastifyjs.css" />

  <script>
    (function () {
      const storedTheme = localStorage.getItem('theme');
      const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const theme = storedTheme || (prefersDarkScheme ? 'dark' : 'light');
      document.documentElement.setAttribute('data-bs-theme', theme);
    })();

  </script>

</head>

<body>
  <div class="wrapper-sticky">
    <nav class="navbar navbar-expand-lg bg-body fixed-top">
      <div class="container">
        <a class="navbar-brand" href="<?= "" ?>">
          <img class="brand-logo-ligth" src="<?= site_logo($config->get("white_logo")) ?>" alt="Logo Light"
            class="logo-light" height="40">
          <img class="brand-logo-dark" src="<?= site_logo($config->get("dark_logo")) ?>" alt="Logo Dark"
            class="logo-dark d-none" height="40">
        </a>
        <button class="navbar-toggler me-1 ms-auto" type="button" data-bs-toggle="offcanvas"
          data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" id="navbarResponsive">
          <div class="offcanvas-header">
            <a class="navbar-brand" href="<?= "" ?>">
              <img class="brand-logo-ligth" src="<?= site_logo($config->get("white_logo")) ?>" alt="Logo Light"
                class="logo-light" height="40">
              <img class="brand-logo-dark" src="<?= site_logo($config->get("dark_logo")) ?>" alt="Logo Dark"
                class="logo-dark d-none" height="40">
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav align-items-lg-center justify-content-end flex-grow-1 pe-1">
              <li class="nav-item">
                <a class="nav-link" href="<?= SITE_URL ?>">
                  <i class="fa fa-home"></i>
                  Inicio
                </a>
              </li>
              <?php if (!is_signed_in()): ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?= SITE_URL ?>/signin">
                    <i class="fa fa-key"></i>
                    Iniciar Sesión
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= SITE_URL ?>/signup">
                    <i class="fa fa-plus"></i>
                    Registrarse
                  </a>
                </li>
              <?php endif; ?>
              <?php if (is_signed_in()): ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?= SITE_URL ?>/profile">
                    <i class="fa fa-user"></i>
                    Perfil
                  </a>
                </li>
              <?php endif; ?>
              <?php if (is_signed_in()): ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?= SITE_URL ?>/signout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar Sesión
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
        <ul class="navbar-nav">
          <li class="nav-item">
            <button class="nav-link py-2 d-flex align-items-center" id="bd-theme-toggle" type="button"><span
                class="theme-icon-active mr-1" id="bd-theme-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun">
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
          </li>
        </ul>
      </div>
    </nav>

    <main class="main-content">
      <?php
      $view = path_front_view($template["path"] ?? "");
      if ($view["success"]):
        include_once $view["file"];
      else: ?>
        <div class='w-50 m-auto alert alert-danger'><?= $view['message'] ?></div>
      <?php endif; ?>
    </main>

    <footer class="footer bg-body">
      <div class="container text-center">
        Copyright © <?= date("Y") ?> <a href="http://github.com/pirulug" target="_blank"
          rel="noopener noreferrer">Pirulug</a>.
        Todos
        los derechos reservados.
        <div id="animated-counter" class="mt-2"></div>
      </div>
    </footer>
  </div>

  <script src="<?= SITE_URL ?>/static/plugins/toastifyjs/toastifyjs.js"></script>
  <script src="<?= SITE_URL ?>/static/assets/js/piruui.js"></script>

  <?= $notifier->showToasts(); ?>

</body>

</html>