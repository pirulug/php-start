<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <!-- Primary Meta Tags-->
  <title><?= $theme_title ?> | <?= SITE_NAME ?></title>

  <!-- Favicon-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_URL ?>/admin/assets/img/favicon/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= SITE_URL ?>/admin/assets/img/favicon/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="i<?= SITE_URL ?>/admin/assets/mg/favicon/favicon-16x16.png" />
  <link rel="manifest" href="<?= SITE_URL ?>/admin/assets/img/favicon/site.webmanifest" />

  <!-- Css -->
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/assets/css/fontawesome.css" />
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/assets/css/toastifyjs.css" />
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/assets/css/piruadmin.css" />

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
  <?php block("style"); ?>
</head>

<body>
  <main class="wrapper">