<?php

/**
 * SYNC PERMISSIONS - PiruLMS
 * Sincroniza los permisos definidos en los archivos router.php con la base de datos.
 * Los archivos son la fuente de verdad.
 */

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . "/config.php";
require_once BASE_DIR . "/core/bootstrap/base.php";

echo "--- INICIANDO SINCRONIZACIÓN DE PERMISOS ---\n" . PHP_EOL;

// 1. Configuración de Contextos y Rutas
$context_dirs = [
    'admin' => BASE_DIR . '/app/admin/modules/*/router.php',
    'front' => BASE_DIR . '/app/home/modules/*/router.php',
    'api'   => BASE_DIR . '/app/api/*/router.php',
    'ajax'  => BASE_DIR . '/app/ajax/*/router.php',
];

// 2. Extraer permisos de los archivos
$found_permissions = []; // [ 'context_key' => [ 'permission_key_name' => true ] ]

foreach ($context_dirs as $context => $pattern) {
    $files = glob($pattern);
    foreach ($files as $file) {
        $content = file_get_contents($file);
        // Expresión regular para encontrar ->permission("clave") o ->permission('clave')
        preg_match_all('/->permission\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $perm_key) {
                $found_permissions[$context][$perm_key] = true;
            }
        }
    }
}

// 3. Obtener contextos de la base de datos
$stmt_contexts = $connect->query("SELECT permission_context_key, permission_context_id FROM permission_contexts");
$db_contexts = $stmt_contexts->fetchAll(PDO::FETCH_KEY_PAIR); // [ 'key' => id ]

// 4. Obtener permisos actuales de la base de datos
$stmt_perms = $connect->query("
    SELECT p.permission_id, p.permission_key_name, pc.permission_context_key 
    FROM permissions p
    JOIN permission_contexts pc ON p.permission_context_id = pc.permission_context_id
");
$current_db_perms = $stmt_perms->fetchAll(PDO::FETCH_OBJ);

// 5. Comparar y Sincronizar
$to_add = [];
$to_delete = [];

// Identificar permisos para eliminar (están en DB pero no en archivos)
foreach ($current_db_perms as $db_perm) {
    if (!isset($found_permissions[$db_perm->permission_context_key][$db_perm->permission_key_name])) {
        $to_delete[] = $db_perm->permission_id;
    }
}

// Identificar permisos para agregar (están en archivos pero no en DB)
foreach ($found_permissions as $ctx_key => $perms) {
    if (!isset($db_contexts[$ctx_key])) {
        echo "Excepción: El contexto '{$ctx_key}' no existe en la base de datos. Saltando...\n";
        continue;
    }

    $ctx_id = $db_contexts[$ctx_key];

    foreach ($perms as $perm_key => $_) {
        $exists = false;
        foreach ($current_db_perms as $db_perm) {
            if ($db_perm->permission_key_name === $perm_key && $db_perm->permission_context_key === $ctx_key) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $to_add[] = [
                'key' => $perm_key,
                'ctx_id' => $ctx_id,
                'ctx_key' => $ctx_key
            ];
        }
    }
}

// 6. Ejecutar Operaciones
if (!empty($to_delete)) {
    echo "Eliminando " . count($to_delete) . " permisos obsoletos...\n";
    $placeholders = implode(',', array_fill(0, count($to_delete), '?'));
    $stmt_del = $connect->prepare("DELETE FROM permissions WHERE permission_id IN ($placeholders)");
    $stmt_del->execute($to_delete);
} else {
    echo "No hay permisos para eliminar.\n";
}

if (!empty($to_add)) {
    echo "Agregando " . count($to_add) . " nuevos permisos...\n";
    // Usar el grupo ID 1 (Sistema) por defecto
    $stmt_ins = $connect->prepare("
        INSERT INTO permissions (permission_name, permission_key_name, permission_group_id, permission_context_id) 
        VALUES (:name, :key, 1, :ctx_id)
    ");
    foreach ($to_add as $perm) {
        $stmt_ins->execute([
            'name' => ucfirst(str_replace('.', ' ', $perm['key'])), // Nombre legible básico
            'key'  => $perm['key'],
            'ctx_id' => $perm['ctx_id']
        ]);
        echo " + [{$perm['ctx_key']}] {$perm['key']}\n";
    }
} else {
    echo "No hay permisos nuevos para agregar.\n";
}

echo "\n--- SINCRONIZACIÓN COMPLETADA ---\n" . PHP_EOL;
