<?php

// -----------------------------------------------------------------------------
// SECCIÓN: CARGA DE DOMINIOS Y CORE
// -----------------------------------------------------------------------------

/**
 * Motor de carga para dominios de módulos con sistema de caché.
 * 
 * @param string $context Contexto de ejecución (admin|front).
 */
function load_domains(string $context) {
  $cacheFile  = BASE_DIR . "/storage/caches/domains.{$context}.php";
  $modulesDir = BASE_DIR . "/app/{$context}/modules";

  if (!is_dir($modulesDir))
    return;

  if (CACHE_ROTE === true) {
    if (!is_file($cacheFile)) {
      $buffer  = "<?php\n\n";
      $modules = scandir($modulesDir);

      foreach ($modules as $module) {
        if ($module === '.' || $module === '..')
          continue;

        $domainDir = "{$modulesDir}/{$module}/domains";
        if (is_dir($domainDir)) {
          foreach (scandir($domainDir) as $file) {
            if (str_ends_with($file, '.domain.php')) {
              $fullPath = str_replace('\\', '/', "{$domainDir}/{$file}");
              $baseDirNorm = str_replace('\\', '/', BASE_DIR);
              $relativePath = ltrim(str_replace($baseDirNorm, '', $fullPath), '/');
              $buffer  .= "require_once BASE_DIR . '/{$relativePath}';\n";
            }
          }
        }
      }
      file_put_contents($cacheFile, $buffer);
    }
    require_once $cacheFile;
    return;
  }

  // Sin caché (Desarrollo)
  $modules = scandir($modulesDir);
  foreach ($modules as $module) {
    if ($module === '.' || $module === '..')
      continue;
    $domainDir = "{$modulesDir}/{$module}/domains";
    if (is_dir($domainDir)) {
      foreach (scandir($domainDir) as $file) {
        if (str_ends_with($file, '.domain.php')) {
          require_once "{$domainDir}/{$file}";
        }
      }
    }
  }
}

/**
 * Carga los archivos base del sistema (helpers, libraries, middlewares).
 * 
 * @param string $type Directorio dentro de /core a cargar.
 */
function load_core_files(string $type) {
  $coreCacheFile = BASE_DIR . "/storage/caches/core.{$type}.php";
  $corePath      = BASE_DIR . "/core/{$type}/*.php";

  if (CACHE_ROTE === true) {
    if (!is_file($coreCacheFile)) {
      $cacheBuffer = "<?php\n\n";

      foreach (glob($corePath) as $coreFile) {
        $fullPath      = str_replace('\\', '/', $coreFile);
        $baseDirNorm   = str_replace('\\', '/', BASE_DIR);
        $relativePath  = ltrim(str_replace($baseDirNorm, '', $fullPath), '/');
        $cacheBuffer  .= "require_once BASE_DIR . '/{$relativePath}';\n";
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

// -----------------------------------------------------------------------------
// SECCIÓN: ENRUTAMIENTO (ROUTES)
// -----------------------------------------------------------------------------

/**
 * Carga dinámicamente las rutas del frontend.
 */
function load_routes_front() {
  $cacheFile = BASE_DIR . "/storage/caches/routes.home.php";
  
  // Obtener los módulos unificados de base de datos
  $modules_setting = site_config()->get("modules");
  $front_modules_setting = ($modules_setting && isset($modules_setting->front)) ? $modules_setting->front : null;
  
  $modules = [];
  if ($front_modules_setting && is_object($front_modules_setting)) {
    $modules_order = [];
    foreach ($front_modules_setting as $module_name => $setting) {
      if (isset($setting->active) && $setting->active) {
        $order = isset($setting->order) ? (int)$setting->order : 999;
        $modules_order[$module_name] = $order;
      }
    }
    asort($modules_order);
    $modules = array_fill_keys(array_keys($modules_order), true);
  } else {
    $modules = require BASE_DIR . "/app/front/modules.php";
  }

  if (CACHE_ROTE === true) {
    if (!is_file($cacheFile)) {
      $buffer = "<?php\n\n";

      foreach ($modules as $module => $enabled) {
        if (!$enabled) {
          continue;
        }

        $functions = BASE_DIR . "/app/front/modules/{$module}/functions.php";
        if (is_file($functions)) {
          $buffer .= "require_once BASE_DIR . '/app/front/modules/{$module}/functions.php';\n";
        } else {
          $router = BASE_DIR . "/app/front/modules/{$module}/router.php";
          if (is_file($router)) {
            $buffer .= "require_once BASE_DIR . '/app/front/modules/{$module}/router.php';\n";
          }
        }
      }

      file_put_contents($cacheFile, $buffer);
    }

    require $cacheFile;
    return true;
  }

  // sin cache
  foreach ($modules as $module => $enabled) {
    if (!$enabled) {
      continue;
    }

    $functions = BASE_DIR . "/app/front/modules/{$module}/functions.php";
    if (is_file($functions)) {
      require_once $functions;
    } else {
      $router = BASE_DIR . "/app/front/modules/{$module}/router.php";
      if (is_file($router)) {
        require_once $router;
      }
    }
  }

  return true;
}

/**
 * Carga dinámicamente las rutas del panel administrativo.
 */
function load_routes_admin() {
  Router::prefix(PATH_ADMIN, CTX_ADMIN, function () {
    $cacheFile = BASE_DIR . "/storage/caches/routes.admin.php";
    
    // Obtener los módulos unificados de base de datos
    $modules_setting = site_config()->get("modules");
    $admin_modules_setting = ($modules_setting && isset($modules_setting->admin)) ? $modules_setting->admin : null;
    
    $modules = [];
    if ($admin_modules_setting && is_object($admin_modules_setting)) {
      $modules_order = [];
      foreach ($admin_modules_setting as $module_name => $setting) {
        if (isset($setting->active) && $setting->active) {
          $order = isset($setting->order) ? (int)$setting->order : 999;
          $modules_order[$module_name] = $order;
        }
      }
      asort($modules_order);
      $modules = array_fill_keys(array_keys($modules_order), true);
    } else {
      $modules = require BASE_DIR . "/app/admin/modules.php";
    }

    if (CACHE_ROTE === true) {
      if (!is_file($cacheFile)) {
        $buffer = "<?php\n\n";
        foreach ($modules as $module => $enabled) {
          if (!$enabled)
            continue;
          
          $functions = BASE_DIR . "/app/admin/modules/{$module}/functions.php";
          if (is_file($functions)) {
            $buffer .= "require_once BASE_DIR . '/app/admin/modules/{$module}/functions.php';\n";
          } else {
            $router = BASE_DIR . "/app/admin/modules/{$module}/router.php";
            if (is_file($router)) {
              $buffer .= "require_once BASE_DIR . '/app/admin/modules/{$module}/router.php';\n";
            }
          }
        }
        file_put_contents($cacheFile, $buffer);
      }
      require $cacheFile;
      return;
    }

    foreach ($modules as $module => $enabled) {
      if (!$enabled)
        continue;
      
      $functions = BASE_DIR . "/app/admin/modules/{$module}/functions.php";
      if (is_file($functions)) {
        require_once $functions;
      } else {
        $router = BASE_DIR . "/app/admin/modules/{$module}/router.php";
        if (is_file($router)) {
          require_once $router;
        }
      }
    }
  });

  return true;
}

/**
 * Carga dinámicamente las rutas de la API global.
 */
function load_routes_api() {
  Router::prefix(PATH_API, CTX_API, function () {
    $cacheFile = BASE_DIR . "/storage/caches/routes.api.php";
    
    // Obtener los módulos unificados de base de datos
    $modules_setting = site_config()->get("modules");
    $api_modules_setting = ($modules_setting && isset($modules_setting->api)) ? $modules_setting->api : null;
    
    $modules = [];
    if ($api_modules_setting && is_object($api_modules_setting)) {
      $modules_order = [];
      foreach ($api_modules_setting as $module_name => $setting) {
        if (isset($setting->active) && $setting->active) {
          $order = isset($setting->order) ? (int)$setting->order : 999;
          $modules_order[$module_name] = $order;
        }
      }
      asort($modules_order);
      $modules = array_fill_keys(array_keys($modules_order), true);
    } else {
      $modules = require BASE_DIR . "/app/api/modules.php";
    }

    if (CACHE_ROTE === true) {
      if (!is_file($cacheFile)) {
        $buffer = "<?php\n\n";

        foreach ($modules as $module => $enabled) {
          if (!$enabled) {
            continue;
          }

          $functions = BASE_DIR . "/app/api/{$module}/functions.php";
          if (is_file($functions)) {
            $buffer .= "require_once BASE_DIR . '/app/api/{$module}/functions.php';\n";
          } else {
            $router = BASE_DIR . "/app/api/{$module}/router.php";
            if (is_file($router)) {
              $buffer .= "require_once BASE_DIR . '/app/api/{$module}/router.php';\n";
            }
          }
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

      $functions = BASE_DIR . "/app/api/{$module}/functions.php";
      if (is_file($functions)) {
        require_once $functions;
      } else {
        $router = BASE_DIR . "/app/api/{$module}/router.php";
        if (is_file($router)) {
          require_once $router;
        }
      }
    }
  });

  return true;
}

/**
 * Carga las rutas de los servicios internos del sistema.
 */
function load_routes_services() {
  $cacheFile   = BASE_DIR . '/storage/caches/routes.services.php';
  $servicesDir = BASE_DIR . '/core/services';

  if (CACHE_ROTE === true) {
    if (!is_file($cacheFile)) {
      $buffer = "<?php\n\n";
      foreach (glob($servicesDir . '/*/router.php') as $routerFile) {
        $fullPath = str_replace('\\', '/', $routerFile);
        $baseDirNorm = str_replace('\\', '/', BASE_DIR);
        $relativePath = ltrim(str_replace($baseDirNorm, '', $fullPath), '/');
        $buffer .= "require_once BASE_DIR . '/{$relativePath}';\n";
      }
      file_put_contents($cacheFile, $buffer);
    }
    require $cacheFile;
    return;
  }

  // sin cache
  if (!is_dir($servicesDir))
    return;
  foreach (glob($servicesDir . '/*/router.php') as $routerFile) {
    require_once $routerFile;
  }
}

// -----------------------------------------------------------------------------
// SECCIÓN: COMPONENTES DE INTERFAZ (UI)
// -----------------------------------------------------------------------------

/**
 * Carga los archivos sidebar.php de cada módulo activo para construir el menú.
 */
function load_admin_sidebar() {
  $menuCacheFile = BASE_DIR . "/storage/caches/sidebar.admin.php";
  
  // Obtener los módulos y su orden de base de datos
  $modules_setting = site_config()->get("modules");
  $admin_modules_setting = ($modules_setting && isset($modules_setting->admin)) ? $modules_setting->admin : null;
  
  $modules = [];
  if ($admin_modules_setting && is_object($admin_modules_setting)) {
    $modules_order = [];
    foreach ($admin_modules_setting as $module_name => $setting) {
      $active = isset($setting->active) && $setting->active;
      $show_sidebar = !isset($setting->sidebar) || (bool)$setting->sidebar;
      
      if ($active && $show_sidebar) {
        $order = isset($setting->order) ? (int)$setting->order : 999;
        $modules_order[$module_name] = $order;
      }
    }
    // Ordenar por valor de order (ascendente)
    asort($modules_order);
    $modules = array_fill_keys(array_keys($modules_order), true);
  } else {
    $modules = require BASE_DIR . "/app/admin/modules.php";
  }

  if (CACHE_ROTE === true) {
    if (!is_file($menuCacheFile)) {
      $cacheBuffer = "<?php\n\n";

      foreach ($modules as $moduleName => $isEnabled) {
        if (!$isEnabled) {
          continue;
        }

        $functions = BASE_DIR . "/app/admin/modules/{$moduleName}/functions.php";
        if (is_file($functions)) {
          $cacheBuffer .= "require_once BASE_DIR . '/app/admin/modules/{$moduleName}/functions.php';\nSidebar::resetGroup();\n";
        } else {
          $menuFilePath = BASE_DIR . "/app/admin/modules/{$moduleName}/sidebar.php";
          if (is_file($menuFilePath)) {
            $cacheBuffer .= "require_once BASE_DIR . '/app/admin/modules/{$moduleName}/sidebar.php';\nSidebar::resetGroup();\n";
          }
        }
      }

      file_put_contents($menuCacheFile, $cacheBuffer);
    }

    require $menuCacheFile;
    return;
  }

  // sin cache
  foreach ($modules as $moduleName => $isEnabled) {
    if (!$isEnabled) {
      continue;
    }

    $functions = BASE_DIR . "/app/admin/modules/{$moduleName}/functions.php";
    if (is_file($functions)) {
      require_once $functions;
      Sidebar::resetGroup();
    } else {
      $menuFilePath = BASE_DIR . "/app/admin/modules/{$moduleName}/sidebar.php";
      if (is_file($menuFilePath)) {
        require_once $menuFilePath;
        Sidebar::resetGroup();
      }
    }
  }
}

// -----------------------------------------------------------------------------
// SECCIÓN: INTERNACIONALIZACIÓN (I18N)
// -----------------------------------------------------------------------------

/**
 * Carga automáticamente las traducciones de los módulos para un contexto dado.
 * 
 * @param string $context Contexto de ejecución (admin|front).
 */
function load_module_languages($context) {
  $locale = get_locale();
  $cacheFile = BASE_DIR . "/storage/caches/lang_{$context}_{$locale}.cache.php";
  
  // 1. Intentar cargar desde caché (Alto rendimiento)
  if (is_file($cacheFile)) {
    require_once $cacheFile;
    return;
  }

  // 2. Si no hay caché, escanear módulos
  $modulesDir = BASE_DIR . "/app/{$context}/modules";
  if (!is_dir($modulesDir)) return;
  
  $modules = scandir($modulesDir);
  $all_translations = [];

  foreach ($modules as $module) {
    if ($module === '.' || $module === '..') continue;
    
    // Cargar idioma base (es) y el actual (locale) para asegurar fallbacks
    $baseFile = "{$modulesDir}/{$module}/languages/es.php";
    $langFile = "{$modulesDir}/{$module}/languages/{$locale}.php";
    
    $module_data = [];
    
    if (is_file($baseFile)) {
      $baseData = require $baseFile;
      if (is_array($baseData)) $module_data = $baseData;
    }
    
    if ($locale !== 'es' && is_file($langFile)) {
      $langData = require $langFile;
      if (is_array($langData)) {
        $module_data = array_merge($module_data, $langData);
      }
    }

    if (!empty($module_data)) {
      $all_translations["{$context}.{$module}"] = $module_data;
    }
  }

  // 3. Generar archivo de caché consolidado
  $buffer = "<?php\n\n";
  $buffer .= "/**\n * Archivo de caché de idiomas consolidado\n";
  $buffer .= " * Contexto: {$context} | Idioma: {$locale}\n";
  $buffer .= " * Generado: " . date('Y-m-d H:i:s') . "\n */\n\n";
  
  foreach ($all_translations as $domain => $data) {
    $buffer .= "\$GLOBALS['translations']['{$domain}'] = " . var_export($data, true) . ";\n";
  }
  
  if (!is_dir(dirname($cacheFile))) {
    mkdir(dirname($cacheFile), 0777, true);
  }
  
  file_put_contents($cacheFile, $buffer);
  
  // Cargar el buffer generado
  require_once $cacheFile;
}