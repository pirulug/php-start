<?php

/**
 * Generador de Módulos (Scaffolding).
 * 
 * Crea la estructura básica para un nuevo módulo en front, admin o api.
 */

// El entorno y las configuraciones ya vienen cargadas desde el archivo ps

// =============================================================================
// SECCIÓN: CONFIGURACIÓN Y AYUDA
// =============================================================================

$process_action = $argv[2] ?? null;
$plural_name    = $argv[3] ?? null;

if (in_array('--help', $argv) || in_array('-h', $argv)) {
  echo "\nGenerador de Módulos\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps modules create [nombre_plural] [nombre_singular] --context=[front|admin|api]\n";
  echo "Uso: php ps modules add [nombre_plural] [nombre_archivo] -[flags] --context=[front|admin|api]\n\n";
  echo "Parámetros (Acción 'add'):\n";
  echo "  -A   Action  (.action.php)\n";
  echo "  -V   View    (.view.php)\n";
  echo "  -D   Domain  (.domain.php)\n";
  echo "  -E   Endpoint (.endpoint.php)\n";
  echo "  -S   Script  (.script.js)\n";
  echo "  -router   Crea router.php\n";
  echo "  -sidebar  Crea sidebar.php\n";
  echo "\nEjemplo:\n";
  echo "  php ps modules add settings general -AV\n";
  echo "--------------------------------------------------------\n\n";
  exit();
}

// =============================================================================
// SECCIÓN: LÓGICA DE EJECUCIÓN
// =============================================================================

if (!in_array($process_action, ['create', 'add']) || !$plural_name) {
  echo "\n[ERROR] Faltan parámetros obligatorios.\n";
  echo "Uso: php ps modules [create|add] [nombre_plural] ...\n\n";
  exit();
}

// Parsear contexto (por defecto: admin)
$app_context = 'admin';
foreach ($argv as $arg) {
  if (str_starts_with($arg, '--context=')) {
    $app_context = explode('=', $arg)[1];
  }
}

// -----------------------------------------------------------------------------
// BLOQUE: ADD (Agregar archivos a módulo existente)
// -----------------------------------------------------------------------------
if ($process_action === 'add') {
  $file_name = $argv[4] ?? null;
  if (!$file_name) {
    echo "[ERROR] Debes especificar el nombre del archivo (ej: general).\n";
    exit();
  }

  echo "\n[SOLICITADO] Actualizando módulo: {$plural_name} ({$app_context})\n";
  echo "--------------------------------------------------------\n";

  // Detectar flags
  $flags = "";
  foreach ($argv as $arg) {
    if (str_starts_with($arg, '-')) {
      if (!str_starts_with($arg, '--context') && $arg !== '-router' && $arg !== '-sidebar') {
        $flags .= str_replace('-', '', $arg);
      }
    }
  }

  // Si no hay flags de tipo, por defecto Action y View (AV)
  if (empty($flags) && !in_array('-router', $argv) && !in_array('-sidebar', $argv)) {
    $flags = "AV";
  }

  $module_path = "app/{$app_context}/modules/{$plural_name}/";

  if (!is_dir($module_path)) {
    echo "[ERROR] El módulo '{$plural_name}' no existe en '{$app_context}'.\n";
    exit();
  }

  // Procesar flags de archivos
  if (str_contains($flags, 'A')) create_module_file($module_path, "actions", "{$file_name}.action.php");
  if (str_contains($flags, 'V')) create_module_file($module_path, "views", "{$file_name}.view.php");
  if (str_contains($flags, 'D')) create_module_file($module_path, "domains", "{$file_name}.domain.php");
  if (str_contains($flags, 'E')) create_module_file($module_path, "endpoints", "{$file_name}.endpoint.php");
  if (str_contains($flags, 'S')) create_module_file($module_path, "scripts", "{$file_name}.script.js");
  
  // Lógica inteligente para Router y Sidebar
  $has_action = str_contains($flags, 'A');
  $has_view   = str_contains($flags, 'V');
  $label      = ucfirst($file_name);

  // Router
  if (in_array('-router', $argv) || ($has_action && $has_view)) {
    $router_file = $module_path . "router.php";
    $route_line  = "Router::route('{$plural_name}/{$file_name}')\n  ->action('{$plural_name}@{$file_name}')";
    if ($has_view) $route_line .= "->view('{$plural_name}@{$file_name}')->layout('main')";
    $route_line .= "->register();\n";

    if (file_exists($router_file)) {
      $content = file_get_contents($router_file);
      if (!str_contains($content, "'{$plural_name}/{$file_name}'")) {
        file_put_contents($router_file, "\n" . $route_line, FILE_APPEND);
        echo "AÑADIDO: router.php (ruta: {$file_name})\n";
      }
    } else {
      create_module_file($module_path, "", "router.php", "<?php\n\n" . $route_line);
    }
  }

  // Sidebar (solo admin)
  if ($app_context === 'admin' && (in_array('-sidebar', $argv) || ($has_action && $has_view))) {
    $sidebar_file = $module_path . "sidebar.php";
    $sidebar_line = "\${$plural_name}->item('{$label}', admin_route('{$plural_name}/{$file_name}'));\n";

    if (file_exists($sidebar_file)) {
      $content = file_get_contents($sidebar_file);
      if (!str_contains($content, "admin_route('{$plural_name}/{$file_name}')")) {
        file_put_contents($sidebar_file, $sidebar_line, FILE_APPEND);
        echo "AÑADIDO: sidebar.php (ítem: {$label})\n";
      }
    } else {
      $sidebar_init = "<?php\n\n\${$plural_name} = Sidebar::group('{$label}', 'folder');\n" . $sidebar_line;
      create_module_file($module_path, "", "sidebar.php", $sidebar_init);
    }
  }

  echo "--------------------------------------------------------\n";
  echo "[OK] Módulo '{$plural_name}' actualizado con éxito.\n\n";
  exit();
}

// -----------------------------------------------------------------------------
// BLOQUE: CREATE (Crear módulo completo)
// -----------------------------------------------------------------------------

// Obtener nombre en singular
$singular_name = $argv[4] ?? null;
if (str_starts_with($singular_name ?? '', '--context=')) {
  $singular_name = null;
}

if (!$singular_name) {
  $singular_name = auto_singularize($plural_name);
}

// Validar contexto
$valid_contexts = ['admin', 'api', 'front', 'ajax'];
if (!in_array($app_context, $valid_contexts)) {
  echo "[ERROR] Contexto inválido. Permitidos: " . implode(', ', $valid_contexts) . "\n";
  exit();
}

// =============================================================================
// SECCIÓN: DIRECTORIOS
// =============================================================================

$base_module_path = BASE_DIR . "/app/{$app_context}/modules/{$plural_name}";

// Caso especial para API
if ($app_context === 'api' || $app_context === 'ajax') {
  $base_module_path = BASE_DIR . "/app/{$app_context}/{$plural_name}";
}

if (is_dir($base_module_path)) {
  echo "[ERROR] El módulo '{$plural_name}' ya existe en '{$app_context}'.\n";
  exit();
}

echo "\n[SOLICITADO] Creando módulo: {$plural_name} ({$app_context})\n";
echo "--------------------------------------------------------\n";

// Estructura física
mkdir("{$base_module_path}/actions", 0777, true);

if ($app_context === 'admin' || $app_context === 'front') {
  mkdir("{$base_module_path}/views", 0777, true);
  mkdir("{$base_module_path}/scripts", 0777, true);
  mkdir("{$base_module_path}/domains", 0777, true);
  mkdir("{$base_module_path}/endpoints", 0777, true);
}

// =============================================================================
// SECCIÓN: ARCHIVOS BASE
// =============================================================================

$is_admin = ($app_context === 'admin');
$is_front = ($app_context === 'front');
$is_api   = ($app_context === 'api' || $app_context === 'ajax');

// 1. router.php
$router_content = "<?php\n\n";

if ($is_admin) {
  $router_content .= "Router::route('{$plural_name}')\n  ->action('{$plural_name}@list')->view('{$plural_name}@list')->layout('main')->register();\n\n";
  $router_content .= "Router::route('{$singular_name}/new')\n  ->action('{$plural_name}@new')->view('{$plural_name}@new')->layout('main')->register();\n\n";
  $router_content .= "Router::route('{$singular_name}/edit/{id}')\n  ->action('{$plural_name}@edit')->view('{$plural_name}@edit')->layout('main')->register();\n\n";
  $router_content .= "Router::route('{$singular_name}/delete/{id}')\n  ->action('{$plural_name}@delete')->register();\n\n";
  $router_content .= "Router::route('{$singular_name}/deactivate/{id}')\n  ->action('{$plural_name}@deactivate')->register();\n";
} elseif ($is_api) {
  $router_content .= "Router::route('{$plural_name}/list')\n  ->action('{$plural_name}@list')->register();\n\n";
  $router_content .= "Router::route('{$plural_name}/get/{id}')\n  ->action('{$plural_name}@get')->register();\n\n";
  $router_content .= "Router::route('{$plural_name}/create')\n  ->action('{$plural_name}@create')->register();\n\n";
  $router_content .= "Router::route('{$plural_name}/update/{id}')\n  ->action('{$plural_name}@update')->register();\n\n";
  $router_content .= "Router::route('{$plural_name}/delete/{id}')\n  ->action('{$plural_name}@delete')->register();\n";
} else {
  $router_content .= "Router::route('{$plural_name}')\n";
  $router_content .= "  ->action('{$plural_name}@index')\n";
  if ($is_front) {
    $router_content .= "  ->view('{$plural_name}@index')\n  ->layout('main')\n";
  }
  $router_content .= "  ->register();\n";
}

file_put_contents("{$base_module_path}/router.php", $router_content);
echo "GENERADO: router.php\n";

// 2. Acciones y Vistas
$actions = $is_admin ? ['list', 'new', 'edit', 'delete', 'deactivate'] : ($is_api ? ['list', 'get', 'create', 'update', 'delete'] : ['index']);
$views   = $is_admin ? ['list', 'new', 'edit'] : ($is_front ? ['index'] : []);

foreach ($actions as $act) {
  $filename = ($is_admin || $is_front) ? "{$act}.action.php" : "{$act}.php";
  $content  = "<?php\n\n// Lógica para {$plural_name}::{$act}\n";
  if ($is_api) {
    $content .= "\necho json_encode([\n  'success' => true,\n  'message' => 'API Response from {$plural_name}::{$act}'\n]);\n";
  }
  file_put_contents("{$base_module_path}/actions/{$filename}", $content);
}
echo "GENERADO: acciones básicas\n";

foreach ($views as $view) {
  $cName = ucfirst($plural_name);
  $cView = ucfirst($view);
  
  $view_content = "<?php start_block('title') ?>\n";
  $view_content .= "  {$cView} {$cName}\n";
  $view_content .= "<?php end_block() ?>\n\n";

  if ($is_admin) {
    $view_content .= "<?php start_block('breadcrumb'); ?>\n";
    $view_content .= "<?php render_breadcrumb([\n";
    $view_content .= "  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],\n";
    $view_content .= "  ['label' => '{$cName}', 'link' => admin_route('{$plural_name}')],\n";
    $view_content .= "  ['label' => '{$cView}']\n";
    $view_content .= "]) ?>\n";
    $view_content .= "<?php end_block(); ?>\n\n";
  }

  $view_content .= "<?php start_block('css') ?>\n";
  $view_content .= "<link rel=\"stylesheet\" href=\"\">\n";
  $view_content .= "<style>/* STYLE */</style>\n";
  $view_content .= "<?php end_block() ?>\n\n";

  $view_content .= "<?php start_block('js') ?>\n";
  $view_content .= "<script src=\"\"></script>\n";
  $view_content .= "<script>/* SCRIPT */</script>\n";
  $view_content .= "<?php end_block() ?>\n\n";

  if ($is_front) {
    $view_content .= "<div class=\"container py-3\">\n";
  }

  $view_content .= "  <div class=\"card bg-body\">\n";
  $view_content .= "    <div class=\"card-body\">\n";
  $view_content .= "      <h1>{$cView} {$cName}</h1>\n";
  $view_content .= "      <p>Bienvenido a la sección {$view} del módulo {$plural_name}.</p>\n";
  $view_content .= "    </div>\n";
  $view_content .= "  </div>\n";

  if ($is_front) {
    $view_content .= "</div>\n";
  }

  file_put_contents("{$base_module_path}/views/{$view}.view.php", $view_content);
  
  // Scripts asociados
  file_put_contents("{$base_module_path}/scripts/{$view}.script.js", "// Scripts para {$plural_name} {$view}\nconsole.log('{$plural_name} {$view} initialized');\n");
}
if (!empty($views)) echo "GENERADO: vistas y scripts\n";

// Domains y Endpoints base
if ($is_admin || $is_front) {
  file_put_contents("{$base_module_path}/domains/helper.domain.php", "<?php\n\n// Lógica de dominio para {$plural_name}\n");
  
  if ($is_admin) {
    $cPlural   = ucfirst($plural_name);
    $cSingular = ucfirst($singular_name);
    $sidebar_content = "<?php\n\n";
    $sidebar_content .= "\${$plural_name} = Sidebar::group('{$cPlural}', 'folder');\n\n";
    $sidebar_content .= "\${$plural_name}->item('Nuevo {$cSingular}', admin_route('{$singular_name}/new'))\n";
    $sidebar_content .= "  ->can('{$plural_name}.new');\n\n";
    $sidebar_content .= "\${$plural_name}->item('Lista de {$cPlural}', admin_route('{$plural_name}'))\n";
    $sidebar_content .= "  ->can('{$plural_name}.list');\n";
    
    file_put_contents("{$base_module_path}/sidebar.php", $sidebar_content);
    echo "GENERADO: sidebar.php\n";
  }

  file_put_contents("{$base_module_path}/endpoints/list.endpoint.php", "<?php\n\n// Endpoint para {$plural_name}\n");
}

// =============================================================================
// SECCIÓN: REGISTRO
// =============================================================================

$registry_file = BASE_DIR . "/app/{$app_context}/modules.php";
if (is_file($registry_file)) {
  $reg_content = file_get_contents($registry_file);
  if (!str_contains($reg_content, "'{$plural_name}'")) {
    $reg_content = preg_replace('/];?\s*$/', "  '{$plural_name}' => true,\n];\n", trim($reg_content));
    file_put_contents($registry_file, $reg_content);
    echo "ACTUALIZADO: app/{$app_context}/modules.php\n";
  }
}

echo "--------------------------------------------------------\n";
echo "[OK] Módulo '{$plural_name}' creado con éxito.\n\n";

// =============================================================================
// FUNCIONES AUXILIARES
// =============================================================================

/**
 * Crea un archivo dentro de un módulo con una plantilla base.
 */
function create_module_file($base_path, $sub_folder, $filename, $custom_content = null) {
  $target_dir = $base_path . ($sub_folder ? $sub_folder . "/" : "");
  if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

  $full_path = $target_dir . $filename;
  if (file_exists($full_path)) {
    echo "AVISO: El archivo '{$filename}' ya existe. Omitiendo...\n";
    return;
  }

  if ($custom_content !== null) {
    file_put_contents($full_path, $custom_content);
    echo "GENERADO: {$sub_folder}/{$filename} (Personalizado)\n";
    return;
  }

  $ext = pathinfo($filename, PATHINFO_EXTENSION);
  $clean_name = explode('.', $filename)[0];
  $content = "";

  if ($ext === 'php') {
    $content = "<?php\n\n";
    if (str_contains($filename, '.action')) {
      $content .= "// Lógica para la acción: {$clean_name}\n";
    } elseif (str_contains($filename, '.view')) {
      $content .= "<?php start_block('title') ?>\n  " . ucfirst($clean_name) . "\n<?php end_block() ?>\n\n";
      $content .= "<?php start_block('breadcrumb'); ?>\n<?php render_breadcrumb([\n  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],\n  ['label' => '" . ucfirst($clean_name) . "']\n]) ?>\n<?php end_block(); ?>\n\n";
      $content .= "<div class=\"card bg-body\">\n  <div class=\"card-body\">\n    <h1>" . ucfirst($clean_name) . "</h1>\n    <p>Contenido de la vista.</p>\n  </div>\n</div>\n";
    } elseif ($filename === 'router.php') {
      $content .= "// Definición de rutas\n// Router::route('path')->action('module@action')->view('module@view')->register();\n";
    } elseif ($filename === 'sidebar.php') {
      $content .= "// Items del sidebar\n// \$group = Sidebar::group('Nombre', 'icon');\n// \$group->item('Link', admin_route('path'));\n";
    } else {
      $content .= "// Archivo: {$filename}\n";
    }
  } elseif ($ext === 'js') {
    $content = "// Scripts para {$clean_name}\nconsole.log('{$clean_name} initialized');\n";
  }

  file_put_contents($full_path, $content);
  echo "GENERADO: {$sub_folder}/{$filename}\n";
}

function auto_singularize(string $plural): string {
  if (str_ends_with($plural, 'es')) return substr($plural, 0, -2);
  if (str_ends_with($plural, 's')) return substr($plural, 0, -1);
  return $plural;
}