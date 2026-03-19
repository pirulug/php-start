<?php

$action = $argv[1] ?? null;
$plural = $argv[2] ?? null;

if ($action !== 'create' || !$plural) {
    echo "Uso: php comander/modules.php create <plural> [singular] [--context=admin|home|api|ajax]\n";
    exit(1);
}

// Obtener singular opcional
$singular = $argv[3] ?? null;

// Parse args (context)
$context = 'admin'; // default
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--context=')) {
        $context = explode('=', $arg)[1];
    }
}

// Si el tercer arg no fue singular, sino --context, tratemos de autocompletar el singular
if (str_starts_with($singular ?? '', '--context=')) {
    $singular = null;
}
if (!$singular) {
    // Intento básico de singularizar (quitar s o es)
    if (str_ends_with($plural, 'es')) {
        $singular = substr($plural, 0, -2);
    } elseif (str_ends_with($plural, 's')) {
        $singular = substr($plural, 0, -1);
    } else {
        $singular = $plural;
    }
}

$validContexts = ['admin', 'api', 'home', 'ajax'];
if (!in_array($context, $validContexts)) {
    echo "Contexto inválido. Contextos permitidos: " . implode(', ', $validContexts) . "\n";
    exit(1);
}

$baseDir = __DIR__ . "/../app/{$context}/modules/{$plural}";

// En API y AJAX los módulos van directo a app/api/{modulo} o app/ajax/{modulo}
if ($context === 'api' || $context === 'ajax') {
    $baseDir = __DIR__ . "/../app/{$context}/{$plural}";
}

if (is_dir($baseDir)) {
    echo "El módulo '{$plural}' ya existe en el contexto '{$context}'.\n";
    exit(1);
}

// 1. Create directories
mkdir("{$baseDir}/actions", 0777, true);
if ($context === 'admin' || $context === 'home') {
    mkdir("{$baseDir}/views", 0777, true);
}

// ============================================
// 2. Generate router.php
// ============================================
$routerContent = "<?php\n\n";

if ($context === 'admin' || $context === 'home') {
    // LIST
    $routerContent .= "Router::route('{$plural}')\n";
    $routerContent .= "  ->action({$context}_action(\"{$plural}.list\"))\n";
    $routerContent .= "  ->view({$context}_view(\"{$plural}.list\"))\n";
    $routerContent .= "  ->layout({$context}_layout())\n";
    if ($context === 'admin') {
        $routerContent .= "  ->middleware('auth_{$context}')\n";
        $routerContent .= "  ->permission(\"{$plural}.list\")\n";
    }
    $routerContent .= "  ->register();\n\n";

    // NEW
    $routerContent .= "Router::route('{$singular}/new')\n";
    $routerContent .= "  ->action({$context}_action(\"{$plural}.new\"))\n";
    $routerContent .= "  ->view({$context}_view(\"{$plural}.new\"))\n";
    $routerContent .= "  ->layout({$context}_layout())\n";
    if ($context === 'admin') {
        $routerContent .= "  ->middleware('auth_{$context}')\n";
        $routerContent .= "  ->permission(\"{$plural}.new\")\n";
    }
    $routerContent .= "  ->register();\n\n";

    // EDIT
    $routerContent .= "Router::route('{$singular}/edit/{id}')\n";
    $routerContent .= "  ->action({$context}_action(\"{$plural}.edit\"))\n";
    $routerContent .= "  ->view({$context}_view(\"{$plural}.edit\"))\n";
    $routerContent .= "  ->layout({$context}_layout())\n";
    if ($context === 'admin') {
        $routerContent .= "  ->middleware('auth_{$context}')\n";
        $routerContent .= "  ->permission(\"{$plural}.edit\")\n";
    }
    $routerContent .= "  ->register();\n\n";

    // DELETE (Sin vista)
    $routerContent .= "Router::route('{$singular}/delete/{id}')\n";
    $routerContent .= "  ->action({$context}_action(\"{$plural}.delete\"))\n";
    if ($context === 'admin') {
        $routerContent .= "  ->middleware('auth_{$context}')\n";
        $routerContent .= "  ->permission(\"{$plural}.delete\")\n";
    }
    $routerContent .= "  ->register();\n";

} else {
    // API or AJAX (Minimal routes)
    $routerContent .= "Router::route('{$plural}')\n";
    $routerContent .= "  ->action(BASE_DIR . \"/app/{$context}/{$plural}/actions/list.php\")\n";
    $routerContent .= "  ->register();\n\n";

    $routerContent .= "Router::route('{$singular}/new')\n";
    $routerContent .= "  ->action(BASE_DIR . \"/app/{$context}/{$plural}/actions/new.php\")\n";
    $routerContent .= "  ->register();\n\n";

    $routerContent .= "Router::route('{$singular}/edit/{id}')\n";
    $routerContent .= "  ->action(BASE_DIR . \"/app/{$context}/{$plural}/actions/edit.php\")\n";
    $routerContent .= "  ->register();\n\n";

    $routerContent .= "Router::route('{$singular}/delete/{id}')\n";
    $routerContent .= "  ->action(BASE_DIR . \"/app/{$context}/{$plural}/actions/delete.php\")\n";
    $routerContent .= "  ->register();\n";
}

file_put_contents("{$baseDir}/router.php", $routerContent);

// ============================================
// 3. Generate menu.php (Admin only)
// ============================================
if ($context === 'admin') {
    $cPlural = ucfirst($plural);
    $cSingular = ucfirst($singular);

    $menuContent = "<?php\n\n";
    $menuContent .= "\${$plural}Group = Sidebar::group('{$cPlural}', 'circle');\n\n";
    $menuContent .= "\${$plural}Group->item('Nuevo {$cSingular}', admin_route('{$singular}/new'))\n";
    $menuContent .= "  ->can('{$plural}.new');\n\n";
    $menuContent .= "\${$plural}Group->item('Lista de {$cPlural}', admin_route('{$plural}'))\n";
    $menuContent .= "  ->can('{$plural}.list');\n";
    
    file_put_contents("{$baseDir}/menu.php", $menuContent);
}

// ============================================
// 4. Generate Actions
// ============================================
$actions = ['list', 'new', 'edit', 'delete'];
foreach ($actions as $act) {
    if ($context === 'admin' || $context === 'home') {
        $actionFile = "{$baseDir}/actions/{$act}.action.php";
    } else {
        $actionFile = "{$baseDir}/actions/{$act}.php"; // Para API y AJAX prefieren no usar .action
    }
    
    $actionContent = "<?php\n\n// Acción: {$act} - ({$plural})\n";
    
    if ($context === 'api' || $context === 'ajax') {
        $actionContent .= "\necho json_encode([\n  'success' => true,\n  'message' => 'API {$plural} {$act}'\n]);\n";
    }
    
    file_put_contents($actionFile, $actionContent);
}

// ============================================
// 5. Generate Views
// ============================================
if ($context === 'admin' || $context === 'home') {
    $views = ['list', 'new', 'edit'];
    foreach ($views as $view) {
        $viewContent = "<!-- Vista {$view} de {$plural} -->\n<div class=\"container-fluid\">\n  <h1>" . ucfirst($view) . " " . ucfirst($plural) . "</h1>\n  <p>Contenido para {$view}...</p>\n</div>\n";
        file_put_contents("{$baseDir}/views/{$view}.view.php", $viewContent);
    }
}

// ============================================
// 6. Update modules.php registry
// ============================================
$registryFile = __DIR__ . "/../app/{$context}/modules.php";
if (is_file($registryFile)) {
    $content = file_get_contents($registryFile);
    // Eliminar espacios de cierre, inyectar el nuevo, re-cerrar.
    $content = preg_replace('/];?\s*$/', "  '{$plural}' => true,\n];\n", trim($content));
    file_put_contents($registryFile, $content);
    echo "Registro app/{$context}/modules.php actualizado.\n";
}

echo "✅ Módulo Completo CRUD '{$plural}' (singular: '{$singular}') creado exitosamente en el contexto '{$context}'!\n";
