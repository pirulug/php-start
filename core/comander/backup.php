<?php

/**
 * Utilidad de Respaldo de Base de Datos SQL (Estilo PHPMyAdmin).
 */

// El entorno y las configuraciones ya vienen cargadas desde el archivo ps

// =============================================================================
// SECCIÓN: CONFIGURACIÓN Y AYUDA
// =============================================================================

if (in_array('--help', $argv) || in_array('-h', $argv)) {
  echo "\nRespaldo de Base de Datos\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps backup\n\n";
  echo "Descripción:\n";
  echo "  Genera un volcado al estilo PHPMyAdmin (Índices al final).\n";
  echo "  El archivo se guardará en: database/backup/\n";
  echo "--------------------------------------------------------\n\n";
  exit();
}

$backup_dir  = BASE_DIR . "/database/backup";
$timestamp   = date('d-m-Y_H-i-s');
$backup_file = $backup_dir . "/{$timestamp}.backup.sql";

if (!is_dir($backup_dir)) {
  mkdir($backup_dir, 0777, true);
}

// =============================================================================
// SECCIÓN: LÓGICA DE EJECUCIÓN
// =============================================================================

echo "\n[SOLICITADO] Iniciando Respaldo\n";
echo "--------------------------------------------------------\n";

try {
  $handle = fopen($backup_file, 'w+');

  $date_now = date('Y-m-d H:i:s');
  $db_name  = DB_NAME;

  fwrite($handle, "-- PHP-Start Database Backup\n");
  fwrite($handle, "-- Fecha: " . $date_now . "\n");
  fwrite($handle, "-- Base de datos: `" . $db_name . "`\n\n");

  fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
  fwrite($handle, "SET AUTOCOMMIT = 0;\n");
  fwrite($handle, "START TRANSACTION;\n");
  fwrite($handle, "SET time_zone = \"+00:00\";\n\n");

  fwrite($handle, "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n");
  fwrite($handle, "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n");
  fwrite($handle, "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n");
  fwrite($handle, "/*!40101 SET NAMES utf8mb4 */;\n\n");

  fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n\n");

  // =============================================================================
  // SECCIÓN: RECOLECCIÓN DE DATOS
  // =============================================================================

  $stmt   = $connect->query("SHOW TABLES");
  $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

  $indices_sql = "";
  $auto_increment_sql = "";

  foreach ($tables as $table) {
    echo "PROCESANDO: $table... ";

    // 1. Estructura base (Sin llaves ni AI)
    fwrite($handle, "-- --------------------------------------------------------\n\n");
    fwrite($handle, "--\n-- Estructura de tabla para la tabla `$table`\n--\n\n");
    fwrite($handle, "DROP TABLE IF EXISTS `$table`;\n");
    
    $cols = $connect->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    $col_defs = [];
    $ai_col = null;

    foreach ($cols as $col) {
      $field   = $col['Field'];
      $type    = $col['Type'];
      $null    = ($col['Null'] === 'NO') ? 'NOT NULL' : 'DEFAULT NULL';
      $default = ($col['Default'] !== null) ? "DEFAULT '" . $col['Default'] . "'" : "";
      
      // Si el default es NULL y ya lo pusimos en $null, lo evitamos duplicar
      if ($col['Null'] === 'YES' && $col['Default'] === null) {
        $default = "";
      }

      $col_defs[] = "  `$field` $type $null $default";
      
      if (str_contains($col['Extra'], 'auto_increment')) {
        $ai_col = $col;
      }
    }

    $status = $connect->query("SHOW TABLE STATUS LIKE '$table'")->fetch(PDO::FETCH_ASSOC);
    $engine    = $status['Engine'];
    $collation = $status['Collation'];

    $create_sql = "CREATE TABLE `$table` (\n" . implode(",\n", $col_defs) . "\n) ENGINE=$engine DEFAULT CHARSET=utf8mb4 COLLATE=$collation;\n\n";
    fwrite($handle, $create_sql);

    // 2. Volcado de datos
    $stmt_data = $connect->query("SELECT * FROM `$table` ");
    $rows      = $stmt_data->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
      fwrite($handle, "--\n-- Volcado de datos para la tabla `$table`\n--\n\n");
      
      $keys         = array_keys($rows[0]);
      $escaped_keys = array_map(fn($key) => "`$key`", $keys);
      $all_values   = [];

      foreach ($rows as $row) {
        $escaped_values = array_map(function ($value) use ($connect) {
          if ($value === null) return 'NULL';
          return $connect->quote($value);
        }, array_values($row));
        $all_values[] = "(" . implode(", ", $escaped_values) . ")";
      }

      $insert_sql = "INSERT INTO `$table` (" . implode(", ", $escaped_keys) . ") VALUES \n" . implode(",\n", $all_values) . ";\n\n";
      fwrite($handle, $insert_sql);
    }

    // 3. Recolectar Índices
    $indices = $connect->query("SHOW INDEX FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    $grouped_indices = [];
    foreach ($indices as $index) {
      $key_name = $index['Key_name'];
      $grouped_indices[$key_name]['unique'] = ($index['Non_unique'] == 0);
      $grouped_indices[$key_name]['columns'][] = "`" . $index['Column_name'] . "`";
    }

    if (!empty($grouped_indices)) {
      $indices_sql .= "--\n-- Indices de la tabla `$table`\n--\n";
      $indices_sql .= "ALTER TABLE `$table` \n";
      $alter_parts = [];
      foreach ($grouped_indices as $key_name => $info) {
        if ($key_name === 'PRIMARY') {
          $alter_parts[] = "  ADD PRIMARY KEY (" . implode(", ", $info['columns']) . ")";
        } elseif ($info['unique']) {
          $alter_parts[] = "  ADD UNIQUE KEY `$key_name` (" . implode(", ", $info['columns']) . ")";
        } else {
          $alter_parts[] = "  ADD KEY `$key_name` (" . implode(", ", $info['columns']) . ")";
        }
      }
      $indices_sql .= implode(",\n", $alter_parts) . ";\n\n";
    }

    // 4. Recolectar Auto Increment
    if ($ai_col) {
      $ai_val = $status['Auto_increment'];
      $field  = $ai_col['Field'];
      $type   = $ai_col['Type'];
      $null   = ($ai_col['Null'] === 'NO') ? 'NOT NULL' : 'DEFAULT NULL';
      
      $auto_increment_sql .= "--\n-- AUTO_INCREMENT de la tabla `$table`\n--\n";
      $auto_increment_sql .= "ALTER TABLE `$table` \n";
      $auto_increment_sql .= "  MODIFY `$field` $type $null AUTO_INCREMENT, AUTO_INCREMENT=$ai_val;\n\n";
    }

    echo "[OK]\n";
  }

  // =============================================================================
  // SECCIÓN: ESCRIBIR ÍNDICES AL FINAL
  // =============================================================================

  fwrite($handle, "--\n-- Índices para tablas volcadas\n--\n\n");
  fwrite($handle, $indices_sql);

  fwrite($handle, "--\n-- AUTO_INCREMENT de las tablas volcadas\n--\n\n");
  fwrite($handle, $auto_increment_sql);

  fwrite($handle, "COMMIT;\n\n");
  fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n\n");

  fwrite($handle, "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n");
  fwrite($handle, "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n");
  fwrite($handle, "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n");

  fclose($handle);

  // Crear una copia estática como el respaldo más reciente
  copy($backup_file, $backup_dir . "/database.mariadb.sql");

  echo "--------------------------------------------------------\n";
  echo "[OK] Respaldo completado con éxito.\n";
  echo "ARCHIVO: " . basename($backup_file) . "\n";
  echo "ÚLTIMO: database.mariadb.sql\n\n";

} catch (Exception $e) {
  echo "[ERROR] No se pudo completar el respaldo.\n";
  echo "DETALLE: " . $e->getMessage() . "\n";
  exit();
}