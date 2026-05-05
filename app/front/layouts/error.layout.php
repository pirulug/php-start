<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= get_block('title') ?></title>

  <!-- Favicon-->
  <?php if ($config->favicon()): ?>
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
  <?= static_assets_css("piruui.css") ?>
  <?= static_assets_css("bootstrapicons.css") ?>
  <?= static_assets_css("fontawesome.css") ?>

  <?= get_block('css'); ?>
</head>

<body class="bg-body d-flex align-items-center justify-content-center vh-100">
  <div class="container text-center">
    <?= $content ?>
  </div>

  <!-- JS -->
  <?= static_assets_js("piruui.js") ?>

  <?= get_block('js'); ?>
</body>

</html>
