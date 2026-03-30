<?php

/**
 * Utilidad de Migración de Base de Datos
 * Ejecución: php comander/migrate.php [fresh]
 */

define('BASE_DIR', dirname(__DIR__));

// Cargar configuración de base de datos y app
require_once BASE_DIR . "/config.php";

// Cargar el núcleo del framework
require_once BASE_DIR . "/core/bootstrap/base.php";


echo "\n🚀 Iniciando Migrador de Base de Datos...\n";
echo "------------------------------------------\n";

$is_fresh = isset($argv[1]) && $argv[1] === 'fresh';

// 1. Manejo de comando FRESH (Opcional)
if ($is_fresh) {
  echo "⚠️  Comando 'fresh' detectado. Eliminando tablas existentes...\n";
  
  $connect->exec("SET FOREIGN_KEY_CHECKS = 0;");
  $stmt = $connect->query("SHOW TABLES");
  $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

  foreach ($tables as $table) {
    $connect->exec("DROP TABLE IF EXISTS `$table` CASCADE");
    echo "   - Tabla `$table` eliminada.\n";
  }
  
  $connect->exec("SET FOREIGN_KEY_CHECKS = 1;");
  echo "✅ Base de datos limpia.\n\n";
}

// 2. Buscar archivos SQL en la carpeta /db (Solo archivos numerados)
$migration_files = glob(BASE_DIR . "/db/[0-9]*.sql");

sort($migration_files); // Asegurar orden 01, 02...

if (empty($migration_files)) {
  echo "ℹ️  No se encontraron archivos .sql en la carpeta /db.\n";
  exit;
}

$executed_count = 0;

foreach ($migration_files as $file) {
  $filename = basename($file);
  echo "⏳ Ejecutando: $filename... ";

  try {
    $sql = file_get_contents($file);
    
    if (empty(trim($sql))) {
      echo "⏩ Salteado (archivo vacío).\n";
      continue;
    }

    // Ejecutar el SQL
    $connect->exec($sql);

    echo "✅ [OK]\n";
    $executed_count++;

  } catch (PDOException $e) {
    echo "❌ [ERROR]\n";
    echo "   Detalle: " . $e->getMessage() . "\n";
    echo "------------------------------------------\n";
    echo "🚨 Proceso detenido debido a un error.\n";
    exit(1);
  }
}

echo "------------------------------------------\n";
if ($executed_count > 0) {
  echo "✅ ¡Proceso completado! Se ejecutaron $executed_count archivos.\n";
} else {
  echo "ℹ️  No se realizaron cambios.\n";
}
echo "\n";
