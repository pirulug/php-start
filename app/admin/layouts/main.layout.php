<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $config->title(get_block('title')) ?></title>

  <!-- Favicon-->
  <?php if ($config->favicon() && isset($config->favicon()->{'favicon.ico'})): ?>
    <link rel="shortcut icon"
      href="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'favicon.ico'} ?>" type="image/x-icon">
  <?php else: ?>
    <link rel="shortcut icon" href="<?= APP_URL ?>/static/assets/img/favicon/favicon.ico" type="image/x-icon">
  <?php endif; ?>

  <script>
    (function () {
      const storedTheme = localStorage.getItem('theme');
      const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const theme = storedTheme || (prefersDarkScheme ? 'dark' : 'light');
      document.documentElement.setAttribute('data-bs-theme', theme);
    })();
  </script>

  <!-- CSS -->
  <?= static_assets_css("piruadmin.css") ?>
  <?= static_assets_css("fontawesome.css") ?>

  <?= static_libs_css("sweetalert2", "sweetalert2.css") ?>

  <?= get_block('css'); ?>
</head>

<body>
  <?php if ($config->get("loader_admin") === 'true'): ?>
    <?php require_once BASE_DIR . '/app/admin/layouts/partials/loader.php'; ?>
  <?php endif; ?>

  <div class="wrapper">

    <?php require_once BASE_DIR . '/app/admin/layouts/partials/sidebar.menu.php'; ?>
    <?php require_once BASE_DIR . '/app/admin/layouts/partials/sidebar.php'; ?>

    <div class="sidebar-backdrop"></div>
    <div class="main">
      <!-- NavBar-->
      <nav class="navtop">
        <a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>
        <div class="navbar-collapse collapse">
          <ul class="navbar-nav navbar-align">
            <li class="nav-item">
              <a class="nav-link" href="<?= APP_URL ?>" target="_blank">
                <i class="fa fa-eye"></i>
                Ver sitio
              </a>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                <i class="fa-solid fa-globe"></i>
                <span class="d-none d-sm-inline-block ms-1"><?= strtoupper(get_locale()) ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item d-flex align-items-center gap-2" href="<?= admin_route('account/lang/es') ?>">
                  Español
                  <?php if (get_locale() === 'es'): ?><i class="fa-solid fa-check ms-auto text-success small"></i><?php endif; ?>
                </a>
                <a class="dropdown-item d-flex align-items-center gap-2" href="<?= admin_route('account/lang/en') ?>">
                  English
                  <?php if (get_locale() === 'en'): ?><i class="fa-solid fa-check ms-auto text-success small"></i><?php endif; ?>
                </a>
              </div>
            </li>

            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" id="bd-theme" type="button"
                aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)"><span
                  class="theme-icon-active" id="bd-theme-icon"><i class="fa fa-sun"></i></span><span
                  class="visually-hidden" id="bd-theme-text">Toggle theme</span></a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="light"
                    aria-pressed="false"><i class="fa fa-sun opacity-50 me-2"></i>Light<i
                      class="pr-check fa fa-check ms-auto d-none"></i></button>
                </li>
                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="dark"
                    aria-pressed="false"><i class="fa fa-moon opacity-50 me-2"></i>Dark<i
                      class="pr-check fa fa-check ms-auto d-none"></i></button>
                </li>
                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-bs-theme-value="auto"
                    aria-pressed="true"><i class="fa fa-circle-half-stroke opacity-50 me-2"></i>Auto<i
                      class="pr-check fa fa-check ms-auto d-none"></i></button>
                </li>
              </ul>
            </li>

            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle d-inline-block" href="#"
                data-bs-toggle="dropdown">
                <div class="avatar avatar-sm me-1">
                  <img class="avatar img-fluid rounded me-1"
                    src="<?= storage_uploads($user_session->user_image, "user") ?>"
                    alt="<?= $user_session->user_name ?>" />
                </div>
                <span><?= $user_session->user_display_name ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="<?= admin_route("account/profile") ?>">
                  <i class="align-middle me-1" data-feather="user"></i>
                  Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= admin_route("account/settings/profile") ?>">
                  <i class="align-middle me-1" data-feather="settings"></i>
                  Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= admin_route("logout") ?>">
                  <i class="align-middle me-1" data-feather="log-out"></i>
                  Cerrar Session
                </a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Content-->
      <main class="content">
        <?php if (has_block('title')): ?>
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <h1 class="h3 d-inline align-middle">
                <?= get_block('title'); ?>
              </h1>
              <?php if (has_block('breadcrumb')): ?>
                <?= get_block('breadcrumb'); ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>


        <?php $notifier->showBootstrap() ?>

        <?php echo $content; ?>

      </main>
      <!-- Footer-->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row text-muted">
            <div class="col-6 text-start">
              <p class="mb-0">
                <a class="text-muted" href="index.html">
                  &copy;
                  <strong><?= $config->siteName() ?></strong>
                </a>All Right Reserved.
              </p>
            </div>
            <div class="col-6 text-end">
              <p class="mb-0">
                Designed By&nbsp;<a class="text-muted" href="http://github.com/pirulug" target="_blank">Pirulug</a></p>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <!-- Back to top-->
    <!-- <a class="btn btn-lg btn-primary btn-lg-square back-to-top" href="#">
      <i class="fa fa-arrow-up"></i>
    </a> -->
  </div>
  <!-- Dark & Ligth-->

  <!-- JS -->
  <?= static_assets_js("piruadmin.js") ?>
  <?= static_libs_js("feathericons", "feathericons.js") ?>
  <?= static_libs_js("sweetalert2", "sweetalert2.js") ?>
  <?= static_libs_js("sweetalert2", "sa.js") ?>

  <?= get_block('js'); ?>
</body>

</html>