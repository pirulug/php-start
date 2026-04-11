<?php

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
$db_contexts   = $stmt_contexts->fetchAll(PDO::FETCH_KEY_PAIR); // [ 'key' => id ]

// 4. Obtener permisos actuales de la base de datos
$stmt_perms       = $connect->query("
    SELECT p.permission_id, p.permission_key_name, pc.permission_context_key 
    FROM permissions p
    JOIN permission_contexts pc ON p.permission_context_id = pc.permission_context_id
");
$current_db_perms = $stmt_perms->fetchAll(PDO::FETCH_OBJ);

// 5. Comparar y Sincronizar
$to_add    = [];
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
        'key'     => $perm_key,
        'ctx_id'  => $ctx_id,
        'ctx_key' => $ctx_key
      ];
    }
  }
}

// 6. Ejecutar Operaciones
if (!empty($to_delete)) {
  echo "Eliminando " . count($to_delete) . " permisos obsoletos...\n";
  $placeholders = implode(',', array_fill(0, count($to_delete), '?'));
  $stmt_del     = $connect->prepare("DELETE FROM permissions WHERE permission_id IN ($placeholders)");
  $stmt_del->execute($to_delete);
} else {
  echo "No hay permisos para eliminar.\n";
}

if (!empty($to_add)) {
  echo "Agregando " . count($to_add) . " nuevos permisos...\n";

  $auto_group   = in_array('--group', $argv);
  $groups_cache = [];

  if ($auto_group) {
    $stmt_groups  = $connect->query("SELECT permission_group_key_name, permission_group_id FROM permission_groups");
    $groups_cache = $stmt_groups->fetchAll(PDO::FETCH_KEY_PAIR);
  }

  $stmt_ins = $connect->prepare("
        INSERT INTO permissions (permission_name, permission_key_name, permission_group_id, permission_context_id) 
        VALUES (:name, :key, :group_id, :ctx_id)
    ");

  $stmt_new_group = $connect->prepare("
        INSERT INTO permission_groups (permission_group_name, permission_group_key_name) 
        VALUES (:name, :key)
    ");

  foreach ($to_add as $perm) {
    $group_id = 1; // Grupo por defecto (Sistema)

    if ($auto_group) {
      $parts = explode('.', $perm['key']);
      if (count($parts) > 1) {
        $group_prefix = strtolower($parts[0]);
        $group_name   = ucfirst($group_prefix);

        if (isset($groups_cache[$group_prefix])) {
          $group_id = $groups_cache[$group_prefix];
        } else {
          $stmt_new_group->execute([
            'name' => $group_name,
            'key'  => $group_prefix
          ]);
          $group_id                    = $connect->lastInsertId();
          $groups_cache[$group_prefix] = $group_id;
          echo " * Nuevo grupo auto-generado: {$group_name}\n";
        }
      }
    }

    $stmt_ins->execute([
      'name'     => ucfirst(str_replace('.', ' ', $perm['key'])), // Nombre legible básico
      'key'      => $perm['key'],
      'group_id' => $group_id,
      'ctx_id'   => $perm['ctx_id']
    ]);
    echo " + [{$perm['ctx_key']}] {$perm['key']}\n";
  }
} else {
  echo "No hay permisos nuevos para agregar.\n";
}

$auto_group_enabled = in_array('--group', $argv);
if ($auto_group_enabled) {
  echo "\nRevisando agrupamiento de permisos existentes...\n";
  $updated_count = 0;
  
  $stmt_groups  = $connect->query("SELECT permission_group_key_name, permission_group_id FROM permission_groups");
  $global_groups_cache = $stmt_groups->fetchAll(PDO::FETCH_KEY_PAIR);

  $stmt_new_group = $connect->prepare("
    INSERT INTO permission_groups (permission_group_name, permission_group_key_name) 
    VALUES (:name, :key)
  ");

  $get_auto_group_existing = function ($perm_key) use (&$global_groups_cache, $stmt_new_group, $connect) {
      $parts = explode('.', $perm_key);
      if (count($parts) > 1) {
          $group_prefix = strtolower($parts[0]);
          $group_name   = ucfirst($group_prefix);
          
          if (isset($global_groups_cache[$group_prefix])) {
              return (int)$global_groups_cache[$group_prefix];
          } else {
              $stmt_new_group->execute([
                  'name' => $group_name,
                  'key'  => $group_prefix
              ]);
              $new_id = (int)$connect->lastInsertId();
              $global_groups_cache[$group_prefix] = $new_id;
              echo " * Nuevo grupo auto-generado: {$group_name}\n";
              return $new_id;
          }
      }
      return 1;
  };

  $stmt_check = $connect->query("SELECT permission_id, permission_key_name, permission_group_id FROM permissions");
  $all_current = $stmt_check->fetchAll(PDO::FETCH_OBJ);
  
  $stmt_update_group = $connect->prepare("UPDATE permissions SET permission_group_id = :group_id WHERE permission_id = :id");

  foreach ($all_current as $db_perm) {
      $expected_group_id = $get_auto_group_existing($db_perm->permission_key_name);
      
      if ($expected_group_id > 0 && $expected_group_id !== (int)$db_perm->permission_group_id) {
          $stmt_update_group->execute([
              'group_id' => $expected_group_id,
              'id' => $db_perm->permission_id
          ]);
          $updated_count++;
          echo " ~ [Movido] {$db_perm->permission_key_name} al grupo ID {$expected_group_id}\n";
      }
  }
  
  if ($updated_count > 0) {
      echo " ✓ $updated_count permisos existentes fueron reagrupados exitosamente.\n";
  } else {
      echo " ✓ Todos los permisos existentes ya están agrupados correctamente.\n";
  }
}

echo "\n--- SINCRONIZACIÓN COMPLETADA ---\n" . PHP_EOL;
