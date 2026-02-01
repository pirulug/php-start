<?php

function loadAdminRoutes(): void {
  $cacheFile = BASE_DIR . '/storage/cache/routes.admin.php';
  $modules   = require BASE_DIR . '/app/admin/modules.php';

  if (CACHE_ROTE === true) {

    if (!is_file($cacheFile)) {

      $buffer = "<?php\n\n";

      foreach ($modules as $module => $enabled) {
        if (!$enabled) {
          continue;
        }

        $router = BASE_DIR . "/app/admin/modules/{$module}/router.php";
        if (!is_file($router)) {
          continue;
        }

        $code = file_get_contents($router);

        // eliminar apertura <?php
        $code = preg_replace('/^\s*<\?php\s*/', '', $code);

        $buffer .= trim($code) . "\n\n";
      }

      file_put_contents($cacheFile, $buffer);
    }

    require $cacheFile;
    return;
  }

  // sin cache
  foreach ($modules as $module => $enabled) {
    if (!$enabled) {
      continue;
    }

    $router = BASE_DIR . "/app/admin/modules/{$module}/router.php";
    if (is_file($router)) {
      require_once $router;
    }
  }
}
