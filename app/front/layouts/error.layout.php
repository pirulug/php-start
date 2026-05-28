<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#ff0055">
  <?php render_seo_meta("noindex, nofollow"); ?>

  <!-- Favicon-->
  <?php if ($config->favicon()): ?>
    <link rel="shortcut icon"
      href="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'favicon.ico'} ?>" type="image/x-icon">
  <?php else: ?>
    <link rel="shortcut icon" href="<?= APP_URL ?>/static/assets/front/img/favicon/favicon.ico" type="image/x-icon">
  <?php endif; ?>

  <script>
    (function () {
      const storedTheme = localStorage.getItem("theme");
      const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
      const theme = storedTheme || (prefersDarkScheme ? "dark" : "light");
      document.documentElement.setAttribute("data-bs-theme", theme);
    })();

    const APP_URL = "<?= APP_URL ?>";
  </script>

  <!-- CSS -->
  <?= static_libs_css("fontawesome", "fontawesome.css") ?>
  <?= static_libs_css("bootstrapicons", "bootstrapicons.css") ?>
  <?= static_assets_front_css("piruui.css") ?>

  <?= get_block('css'); ?>
</head>

<body class="bg-body d-flex align-items-center justify-content-center vh-100">
  <div class="container text-center">
    <?= $content ?>
  </div>

  <!-- JS -->
  <?= static_assets_front_js("piruui.js") ?>

  <?= get_block('js'); ?>
</body>

</html>