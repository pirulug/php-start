<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
  <meta name="site-url" content="<?= SITE_URL ?>">

  <!-- Primary Meta Tags-->
  <title><?=$template["title"] ?> | <?= SITE_NAME ?></title>

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
</head>

<body>
  <main class="wrapper">
    <!-- Contenido principal -->
    <?php include_once path_admin_view($template["path"] ?? ""); ?>
  </main>

  <!-- Js Bootstrap-->
  <script src="<?= SITE_URL ?>/static/plugins/feathericons/feathericons.js"></script>
  <script src="<?= SITE_URL ?>/static/plugins/toastifyjs/toastifyjs.js"></script>
  <script src="<?= SITE_URL ?>/static/plugins/sweetalert2/sweetalert2.js"></script>
  <script src="<?= SITE_URL ?>/static/plugins/sweetalert2/sa.js"></script>
  <script src="<?= SITE_URL ?>/static/assets/js/piruadmin.js"></script>

  <!-- Mostrar las notificaciones -->
  <?= $notifier->showToasts(); ?>
  <?= $notifier->showSweetAlerts(); ?>
</body>

</html>