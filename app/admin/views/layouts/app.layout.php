<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
  <meta name="site-url" content="<?= SITE_URL ?>">

  <!-- Primary Meta Tags-->
  <title><?= $theme_title ?> | <?= SITE_NAME ?></title>

  <!-- Favicon-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_URL ?>/static/assets/img/favicon/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= SITE_URL ?>/static/assets/img/favicon/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="i<?= SITE_URL ?>/static/assets/mg/favicon/favicon-16x16.png" />
  <link rel="manifest" href="<?= SITE_URL ?>/static/assets/img/favicon/site.webmanifest" />

  <!-- Css -->
  <link rel="stylesheet" href="<?= SITE_URL ?>/static/assets/css/fontawesome.css" />
  <link rel="stylesheet" href="<?= SITE_URL ?>/static/assets/css/piruadmin.css" />

  <link rel="stylesheet" href="<?= SITE_URL ?>/static/plugins/toastifyjs/toastifyjs.css" />
  <link rel="stylesheet" href="<?= SITE_URL ?>/static/plugins/sweetalert2/sweetalert2.css" />
  <!--  -->
  <script>
    (function () {
      const storedTheme = localStorage.getItem("theme");
      const prefersDarkScheme = window.matchMedia(
        "(prefers-color-scheme: dark)",
      ).matches;
      const theme = storedTheme || (prefersDarkScheme ? "dark" : "light");
      document.documentElement.setAttribute("data-bs-theme", theme);
    })();
  </script>

  <!-- Block Style -->
  <?php $theme->block("style"); ?>
</head>

<body>
  <main class="wrapper">

    <!-- Menu -->
    <?php include BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>

    <div class="main">
      <!-- NavBar-->
      <nav class="navtop">
        <a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>
        <div class="navbar-collapse collapse">
          <ul class="navbar-nav navbar-align">
            <li class="nav-item">
              <a class="nav-link" href="<?= SITE_URL ?>" target="_blank">
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
                <img class="avatar img-fluid rounded me-1"
                  src="<?= SITE_URL . "/uploads/user/" . $user_session->user_image ?>"
                  alt="<?= $user_session->user_name ?>" />
                <span><?= $user_session->user_display_name ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="<?= url_admin("account/profile") ?>">
                  <i class="align-middle me-1" data-feather="user"></i>
                  Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= url_admin("account/settings") ?>">
                  <i class="align-middle me-1" data-feather="settings"></i>
                  Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= url_admin("logout") ?>">
                  <i class="align-middle me-1" data-feather="log-out"></i>
                  Cerrar Session
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

        <!-- Mostrar los mensajes de Bootstrap -->
        <?= $notifier->showBootstrap(); ?>
        <?php $theme->block("content"); ?>

      </div>
      <!-- Footer-->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row text-muted">
            <div class="col-6 text-start">
              <p class="mb-0">
                <a class="text-muted" href="index.html"><strong><?= SITE_NAME ?></strong></a>
                &copy;
                <i>Admin dashboard</i>
              </p>
            </div>
            <div class="col-6 text-end">
              <p class="mb-0">
                Designed By&nbsp;
                <a class="text-muted" href="http://github.com/pirulug" target="_blank">Pirulug</a>
              </p>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!-- Js Bootstrap-->
  <script src="<?= SITE_URL ?>/static/plugins/feathericons/feathericons.js"></script>
  <script src="<?= SITE_URL ?>/static/plugins/toastifyjs/toastifyjs.js"></script>
  <script src="<?= SITE_URL ?>/static/plugins/sweetalert2/sweetalert2.js"></script>
  <script src="<?= SITE_URL ?>/static/plugins/sweetalert2/sa.js"></script>
  <script src="<?= SITE_URL ?>/static/assets/js/piruadmin.js"></script>

  <!-- Block Script -->
  <?php $theme->block("script"); ?>

  <!-- Mostrar las notificaciones -->
  <?= $notifier->showToasts(); ?>
  <?= $notifier->showSweetAlerts(); ?>
</body>

</html>