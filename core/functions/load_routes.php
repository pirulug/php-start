<?php

function loadAdminRoutes() {
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

function loadAdminMenu() {

  $menuCacheFile = BASE_DIR . '/storage/cache/menu.admin.php';
  $adminModules  = require BASE_DIR . '/app/admin/modules.php';

  if (CACHE_ROTE === true) {

    if (!is_file($menuCacheFile)) {

      $cacheBuffer = "<?php\n\n";

      foreach ($adminModules as $moduleName => $isEnabled) {
        if (!$isEnabled) {
          continue;
        }

        $menuFilePath = BASE_DIR . "/app/admin/modules/{$moduleName}/menu.php";

        if (!is_file($menuFilePath)) {
          continue;
        }

        $menuCode = file_get_contents($menuFilePath);

        // eliminar apertura <?php
        $menuCode = preg_replace('/^\s*<\?php\s*/', '', $menuCode);

        $cacheBuffer .= trim($menuCode) . "\n\n";
      }

      file_put_contents($menuCacheFile, $cacheBuffer);
    }

    require $menuCacheFile;
    return;
  }

  // sin cache
  foreach ($adminModules as $moduleName => $isEnabled) {
    if (!$isEnabled) {
      continue;
    }

    $menuFilePath = BASE_DIR . "/app/admin/modules/{$moduleName}/menu.php";

    if (is_file($menuFilePath)) {
      require_once $menuFilePath;
    }
  }
}

function loadCoreFiles(string $type) {

  $coreCacheFile = BASE_DIR . "/storage/cache/core.{$type}.php";
  $corePath      = BASE_DIR . "/core/{$type}/*.php";

  if (CACHE_ROTE === true) {

    if (!is_file($coreCacheFile)) {

      $cacheBuffer = "<?php\n\n";

      foreach (glob($corePath) as $coreFile) {
        $cacheBuffer .= "require_once " . var_export($coreFile, true) . ";\n";
      }

      file_put_contents($coreCacheFile, $cacheBuffer);
    }

    require_once $coreCacheFile;
    return;
  }

  // sin cache
  foreach (glob($corePath) as $coreFile) {
    require_once $coreFile;
  }
}

// Load Routes Home
function loadHomeRoutes() {
  $cacheFile = BASE_DIR . '/storage/cache/routes.home.php';
  $modules   = require BASE_DIR . '/app/home/modules.php';

  if (CACHE_ROTE === true) {

    if (!is_file($cacheFile)) {

      $buffer = "<?php\n\n";

      foreach ($modules as $module => $enabled) {
        if (!$enabled) {
          continue;
        }

        $router = BASE_DIR . "/app/home/modules/{$module}/router.php";
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

    $router = BASE_DIR . "/app/home/modules/{$module}/router.php";
    if (is_file($router)) {
      require_once $router;
    }
  }
}

// Load Routes Ajax
function loadAjaxRoutes() {
  $cacheFile = BASE_DIR . '/storage/cache/routes.ajax.php';
  $modules   = require BASE_DIR . '/app/ajax/modules.php';

  if (CACHE_ROTE === true) {

    if (!is_file($cacheFile)) {

      $buffer = "<?php\n\n";

      foreach ($modules as $module => $enabled) {
        if (!$enabled) {
          continue;
        }

        $router = BASE_DIR . "/app/ajax/{$module}/router.php";
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

    $router = BASE_DIR . "/app/ajax/{$module}/router.php";
    if (is_file($router)) {
      require_once $router;
    }
  }
}

// Load Routes Api
function loadApiRoutes() {
  $cacheFile = BASE_DIR . '/storage/cache/routes.api.php';
  $modules   = require BASE_DIR . '/app/api/modules.php';

  if (CACHE_ROTE === true) {

    if (!is_file($cacheFile)) {

      $buffer = "<?php\n\n";

      foreach ($modules as $module => $enabled) {
        if (!$enabled) {
          continue;
        }

        $router = BASE_DIR . "/app/api/{$module}/router.php";
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

    $router = BASE_DIR . "/app/api/{$module}/router.php";
    if (is_file($router)) {
      require_once $router;
    }
  }
}