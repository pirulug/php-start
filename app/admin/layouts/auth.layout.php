<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#ff0055">
  <title><?= $config->title(get_block('title')) ?></title>

  <!-- Favicon-->
  <?php if ($config->favicon()): ?>
    <link rel="shortcut icon"
      href="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'favicon.ico'} ?>" type="image/x-icon">
  <?php else: ?>
    <link rel="shortcut icon" href="<?= APP_URL ?>/static/assets/img/favicon/favicon.ico" type="image/x-icon">
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
  <?= static_assets_css("fontawesome.css") ?>
  <?= static_assets_css("bootstrapicons.css") ?>
  <?= static_assets_css("piruadmin-fonts.css") ?>
  <?= static_assets_css("piruadmin.css") ?>

  <?= get_block('css'); ?>
</head>

<body>

  <main class="w-100">
    <div class="d-flex min-vh-100 align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4">
            <?php $notifier->showBootstrap() ?>
            <?= $content ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- JS -->
  <?= static_assets_js("piruadmin.js") ?>

  <?= get_block('js'); ?>

  <script>
    // Procesar cola de correos de forma asíncrona al cargar la página
    window.addEventListener("DOMContentLoaded", () => {
      setTimeout(() => {
        fetch(APP_URL + "/mail/process").catch((err) => console.error("Error al procesar la cola de correos:", err));
      }, 1000);
    });
  </script>
</body>

</html>