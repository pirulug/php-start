<?php

/**
 * Utilidad de Respaldo de Base de Datos
 * Ejecución: php comander/backup.php
 */

define('BASE_DIR', dirname(__DIR__));

// Cargar configuración de base de datos y app
require_once BASE_DIR . "/config.php";

// Cargar el núcleo del framework
require_once BASE_DIR . "/core/bootstrap/base.php";

$backup_file = BASE_DIR . "/db/database.mariadb.sql";

echo "\n📦 Iniciando Respaldo de Base de Datos...\n";
echo "------------------------------------------\n";

try {
  $handle = fopen($backup_file, 'w+');
  
  fwrite($handle, "-- PHP-Start Database Backup\n");
  fwrite($handle, "-- Fecha: " . date('Y-m-d H:i:s') . "\n");
  fwrite($handle, "-- Base de datos: " . DB_NAME . "\n\n");
  fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n\n");

  // Obtener todas las tablas
  $stmt = $connect->query("SHOW TABLES");
  $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

  foreach ($tables as $table) {
    echo "💾 Procesando tabla: $table... ";
    
    // 1. Estructura della tabla
    $stmt_create = $connect->query("SHOW CREATE TABLE $table ");
    $create_row = $stmt_create->fetch(PDO::FETCH_ASSOC);
    
    fwrite($handle, "-- Estructura de la tabla $table --\n");
    fwrite($handle, "DROP TABLE IF EXISTS $table;\n");
    fwrite($handle, str_replace('`', '', $create_row['Create Table']) . ";\n\n");

    // 2. Datos de la tabla
    $stmt_data = $connect->query("SELECT * FROM $table ");
    $rows = $stmt_data->fetchAll(PDO::FETCH_ASSOC);


    if (count($rows) > 0) {
      fwrite($handle, "-- Datos de la tabla $table --\n");
      
      $keys = array_keys($rows[0]);
      $all_values = [];
      
      foreach ($rows as $row) {
        $values = array_values($row);
        $escaped_values = array_map(function($value) use ($connect) {
          if ($value === null) return 'NULL';
          return $connect->quote($value);
        }, $values);
        $all_values[] = "(" . implode(", ", $escaped_values) . ")";
      }

      $sql = "INSERT INTO $table (" . implode(", ", $keys) . ") VALUES \n" . implode(",\n", $all_values) . ";\n\n";
      fwrite($handle, $sql);
    }
    
    echo "✅\n";
  }

  fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
  fclose($handle);

  echo "------------------------------------------\n";
  echo "✅ ¡Respaldo completado con éxito!\n";
  echo "📂 Archivo: " . basename($backup_file) . "\n\n";

} catch (Exception $e) {
  echo "❌ [ERROR] no se pudo completar el respaldo.\n";
  echo "   Detalle: " . $e->getMessage() . "\n";
  exit(1);
}
