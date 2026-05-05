<?php

/**
 * Script para la sincronización de permisos entre el código y la base de datos.
 * 
 * 1. Escanea los archivos router.php de contextos admin y front.
 * 2. Identifica permisos nuevos o huérfanos.
 * 3. Actualiza la estructura anidada en la tabla 'options' (site_permissions).
 */

if (!defined('BASE_DIR')) {
  define('BASE_DIR', dirname(dirname(__DIR__)));
  require_once BASE_DIR . "/config.php";
  require_once BASE_DIR . "/core/configs/path.config.php";
  require_once BASE_DIR . "/core/bootstraps/console.bootstrap.php";
}

// -----------------------------------------------------------------------------
// SECCIÓN: AYUDA DEL SCRIPT (--help)
// -----------------------------------------------------------------------------
if (in_array('--help', $argv) || in_array('-h', $argv)) {
  echo "\nSincronización de Permisos (PHP-Start Modular)\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps sync-permissions\n\n";
  echo "Descripción:\n";
  echo "  Escanea archivos router.php y sincroniza site_permissions en options.\n";
  echo "  Agrega permisos nuevos y ELIMINA automáticamente los que ya no existen.\n";
  echo "--------------------------------------------------------\n\n";
  exit(0);
}

echo "[SOLICITADO] Iniciando sincronización de permisos (Estructura Anidada)...\n";
echo "--------------------------------------------------------\n";

// -----------------------------------------------------------------------------
// SECCIÓN: EXTRACCIÓN DE PERMISOS DESDE ARCHIVOS
// -----------------------------------------------------------------------------
$context_dirs = [
  'admin' => BASE_DIR . '/app/admin/modules/*/router.php',
  'front' => BASE_DIR . '/app/front/modules/*/router.php',
];

$found_permissions = [
  'admin' => [
    'access.admin' => true
  ],
  'front' => []
];

foreach ($context_dirs as $context => $pattern) {
  $files = glob($pattern);
  foreach ($files as $file) {
    if (!is_file($file)) {
      continue;
    }

    $content = file_get_contents($file);
    // Buscar el patrón ->permission("clave") o ->permission('clave')
    preg_match_all('/->permission\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);

    if (!empty($matches[1])) {
      foreach ($matches[1] as $perm_key) {
        $found_permissions[$context][$perm_key] = true;
      }
    }
  }
}

// -----------------------------------------------------------------------------
// SECCIÓN: CONSULTA DE ESTADO ACTUAL EN BASE DE DATOS
// -----------------------------------------------------------------------------
$sql  = "SELECT option_value FROM options WHERE option_key = 'site_permissions'";
$stmt = $connect->prepare($sql);
$stmt->execute();
$current_json   = $stmt->fetchColumn();
$db_permissions = json_decode((string)$current_json, true) ?: ['admin' => [], 'front' => []];

// Asegurar buckets
if (!isset($db_permissions['admin'])) {
  $db_permissions['admin'] = [];
}
if (!isset($db_permissions['front'])) {
  $db_permissions['front'] = [];
}

// -----------------------------------------------------------------------------
// SECCIÓN: ANÁLISIS DE DIFERENCIAS (SINCRONIZACIÓN)
// -----------------------------------------------------------------------------
$added_count   = 0;
$removed_count = 0;

// Agregar permisos nuevos encontrados en el código
foreach ($found_permissions as $context => $perms) {
  foreach ($perms as $perm_key => $val) {
    // Definir metadatos específicos para permisos de sistema
    $perm_name = null;
    $group_name = null;
    $description = null;

    if ($perm_key === 'access.admin') {
      $perm_name = "Acceso al Panel Administrativo";
      $group_name = "Sistema";
      $description = "Permiso raíz (gatekeeper) para habilitar el acceso al panel administrativo.";
    } else {
      // Para otros, intentar generar nombre desde la clave (ej: users.list -> List)
      $parts = explode('.', $perm_key);
      $perm_name = ucfirst(end($parts));
      $group_name = ucfirst($parts[0] ?? 'General');
      $description = "Sincronizado automáticamente desde el código";
    }

    if (!isset($db_permissions[$context][$perm_key])) {
      $db_permissions[$context][$perm_key] = [
        'name'  => $perm_name,
        'group' => $group_name,
        'desc'  => $description
      ];
      $added_count++;
      echo " + [NUEVO] [{$context}] {$perm_key}\n";
    } elseif ($perm_key === 'access.admin') {
      $db_permissions[$context][$perm_key]['name'] = $perm_name;
      $db_permissions[$context][$perm_key]['group'] = $group_name;
      $db_permissions[$context][$perm_key]['desc'] = $description;
      $updated_count = ($updated_count ?? 0) + 1;
    }
  }
}

// Eliminar permisos de la DB que ya no están en el código
foreach ($db_permissions as $context => &$perms) {
  if (!is_array($perms)) {
    continue;
  }
  
  foreach ($perms as $perm_key => $data) {
    if (!isset($found_permissions[$context][$perm_key])) {
      unset($perms[$perm_key]);
      $removed_count++;
      echo " - [ELIMINADO] [{$context}] {$perm_key}\n";
    }
  }
}
unset($perms);

// -----------------------------------------------------------------------------
// SECCIÓN: PERSISTENCIA Y LIMPIEZA DE CACHÉ
// -----------------------------------------------------------------------------
if ($added_count > 0 || $removed_count > 0 || ($updated_count ?? 0) > 0) {
  $sql_upd = "UPDATE options SET option_value = :val WHERE option_key = 'site_permissions'";
  $stmt_upd = $connect->prepare($sql_upd);
  $new_json = json_encode($db_permissions);
  $stmt_upd->bindParam(':val', $new_json);
  $stmt_upd->execute();

  // Limpiar cachés de configuración
  $cache_files = [
    BASE_DIR . '/storage/caches/site_options.json',
    BASE_DIR . '/storage/caches/site_options.cache.php'
  ];

  foreach ($cache_files as $cache_file) {
    if (is_file($cache_file)) {
      @unlink($cache_file);
    }
  }

  echo "--------------------------------------------------------\n";
  echo "[OK] Sincronización completada.\n";
  echo "     Agregados: {$added_count}\n";
  echo "     Eliminados: {$removed_count}\n";
} else {
  echo "--------------------------------------------------------\n";
  echo "[OK] Todo está sincronizado. No se requirieron cambios.\n";
}

echo "--------------------------------------------------------\n";
echo "[FINALIZADO]\n\n";