<?php

Router::prefix(PATH_ADMIN, CTX_ADMIN, function () {

  $modules = require BASE_DIR . '/app/admin/modules.php';

  foreach ($modules as $module => $enabled) {
    if (!$enabled) {
      continue;
    }
    $router = BASE_DIR . "/app/admin/modules/{$module}/router.php";
    if (is_file($router)) {
      require_once $router;
    }
  }

  Router::route('')
    ->action(admin_action("auth.login"))
    ->view(admin_view("auth.login"))
    ->layout(admin_layout("auth"))
    ->register();
});

