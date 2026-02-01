<?php

Router::prefix(PATH_AJAX, CTX_AJAX, function () {

  $modules = require BASE_DIR . '/app/ajax/modules.php';

  foreach ($modules as $module => $enabled) {
    if (!$enabled) {
      continue;
    }
    $router = BASE_DIR . "/app/ajax/{$module}/router.php";
    if (is_file($router)) {
      require_once $router;
    }
  }

});