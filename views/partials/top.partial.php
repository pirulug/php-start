<!doctype html>
<html lang="es" class="h-100">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />

  <title>
    <?= $page_title ?>
  </title>
  <meta name="title" content="<?= $page_title ?>" />
  <meta name="description" content="<?= $page_description ?>" />

  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= $og_url ?? "" ?>" />
  <meta property="og:title" content="<?= $og_title ?? "" ?>" />
  <meta property="og:description" content="<?= $og_description ?? "" ?>" />
  <meta property="og:image" content="<?= $og_image ?? "" ?>" />

  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="<?= $og_url ?? "" ?>" />
  <meta property="twitter:title" content="<?= $og_title ?? "" ?>" />
  <meta property="twitter:description" content="<?= $og_description ?? "" ?>" />
  <meta property="twitter:image" content="<?= $og_image ?? "" ?>" />

  <link rel="apple-touch-icon" sizes="180x180" href="<?= $url->favicon($brd_apple_touch_icon) ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $url->favicon($brd_favicon_32x32) ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $url->favicon($brd_favicon_16x16) ?>">
  <link rel="manifest" href="<?= $url->favicon($brd_webmanifest) ?>">

  <link rel="stylesheet" href="<?= $url->css("fontawesome.css") ?>" />
  <link rel="stylesheet" href="<?= $url->css("bootstrapicons.css") ?>" />
  <link rel="stylesheet" href="<?= $url->css("toastifyjs.css") ?>" />
  <link rel="stylesheet" href="<?= $url->css("piruui.css") ?>" />
</head>

<body class="d-flex flex-column h-100">