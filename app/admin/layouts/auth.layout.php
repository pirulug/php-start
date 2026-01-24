<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Primary Meta Tags-->
  <title><?= get_block('title', 'Admin'); ?> | PiruAdmin</title>

  <!-- Favicon-->
  <link rel="shortcut icon" href="<?= APP_URL ?>/static/assets/img/favicon/favicon.ico" type="image/x-icon">

  <!-- Css -->
  <link rel="stylesheet" href="<?= APP_URL ?>/static/assets/css/fontawesome.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/static/assets/css/piruadmin.css" />

  <link rel="stylesheet" href="<?= APP_URL ?>/static/plugins/toastifyjs/toastifyjs.css" />
  <link rel="stylesheet" href="<?= APP_URL ?>/static/plugins/sweetalert2/sweetalert2.css" />

  <script>
    (function () {
      const storedTheme = localStorage.getItem('theme');
      const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const theme = storedTheme || (prefersDarkScheme ? 'dark' : 'light');
      document.documentElement.setAttribute('data-bs-theme', theme);
    })();

  </script>

  <?php echo get_block('css'); ?>
</head>

<body>
  <?php if ($config->get("loader")): ?>
    <?php require_once BASE_DIR . '/app/admin/layouts/partials/loader.php'; ?>
  <?php endif; ?>

  <div class="wrapper">


    <?php $notifier->showBootstrap() ?>

    <?= $content; ?>


  </div>
  <!-- Dark & Ligth-->

  <!-- Js -->
  <script src="<?= APP_URL ?>/static/plugins/feathericons/feathericons.js"></script>
  <script src="<?= APP_URL ?>/static/plugins/toastifyjs/toastifyjs.js"></script>
  <script src="<?= APP_URL ?>/static/plugins/sweetalert2/sweetalert2.js"></script>
  <script src="<?= APP_URL ?>/static/plugins/sweetalert2/sa.js"></script>
  <script src="<?= APP_URL ?>/static/assets/js/piruadmin.js"></script>

  <?php echo get_block('js'); ?>

</body>

</html>