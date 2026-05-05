<?php

/**
 * Utilidad de Migración de Base de Datos SQL.
 * 
 * Permite listar, ejecutar todas o una migración específica.
 */

// El entorno y las configuraciones ya vienen cargadas desde el archivo ps

// =============================================================================
// SECCIÓN: CONFIGURACIÓN Y AYUDA
// =============================================================================

$sub_command = $argv[2] ?? 'all';

if (in_array('--help', $argv) || in_array('-h', $argv)) {
  echo "\nMigrador de Base de Datos\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps migrate [sub-comando]\n\n";
  echo "Sub-comandos:\n";
  echo "  list              Muestra la lista de archivos SQL en /database.\n";
  echo "  all               Ejecuta todas las migraciones (por defecto).\n";
  echo "  fresh             Limpia la base de datos y ejecuta todo.\n";
  echo "  [nombre_archivo]  Ejecuta un archivo SQL específico.\n";
  echo "--------------------------------------------------------\n\n";
  exit();
}

$migration_path = BASE_DIR . "/database/";

// =============================================================================
// SECCIÓN: SUB-COMANDO LIST
// =============================================================================

if ($sub_command === 'list') {
  echo "\n[SOLICITADO] Listando archivos de migración\n";
  echo "--------------------------------------------------------\n";
  $files = glob($migration_path . "*.sql");
  
  if (empty($files)) {
    echo "No se encontraron archivos .sql en: " . basename($migration_path) . "\n";
  } else {
    foreach ($files as $file) {
      echo " - " . basename($file) . "\n";
    }
  }
  echo "--------------------------------------------------------\n\n";
  exit();
}

// =============================================================================
// SECCIÓN: MANEJO DE COMANDO FRESH
// =============================================================================

if ($sub_command === 'fresh') {
  echo "\n[SOLICITADO] Reiniciando base de datos\n";
  echo "--------------------------------------------------------\n";

  $connect->exec("SET FOREIGN_KEY_CHECKS = 0;");
  $stmt   = $connect->query("SHOW TABLES");
  $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

  foreach ($tables as $table) {
    $connect->exec("DROP TABLE IF EXISTS $table CASCADE");
    echo "ELIMINADO: $table\n";
  }

  $connect->exec("SET FOREIGN_KEY_CHECKS = 1;");
  echo "[OK] Base de datos limpia.\n\n";
  $sub_command = 'all'; // Continuar con todas las migraciones
}

// =============================================================================
// SECCIÓN: SELECCIÓN DE ARCHIVOS A MIGRAR
// =============================================================================

$migration_files = [];

if ($sub_command === 'all') {
  $migration_files = glob($migration_path . "*.sql");
  sort($migration_files);
} else {
  // Intentar encontrar el archivo específico
  $specific_file = $migration_path . $sub_command;
  if (file_exists($specific_file)) {
    $migration_files[] = $specific_file;
  } else {
    echo "\n[ERROR] El archivo '$sub_command' no existe.\n";
    echo "RUTA: $migration_path\n\n";
    exit();
  }
}

if (empty($migration_files)) {
  echo "[OK] Nada que migrar.\n";
  exit();
}

// =============================================================================
// SECCIÓN: LÓGICA DE EJECUCIÓN
// =============================================================================

echo "\n[SOLICITADO] Ejecutando migraciones\n";
echo "--------------------------------------------------------\n";

$executed_count = 0;

foreach ($migration_files as $file) {
  $filename = basename($file);
  echo "PROCESANDO: $filename... ";

  try {
    $sql_content = file_get_contents($file);

    if (empty(trim($sql_content))) {
      echo "[SALTEADO]\n";
      continue;
    }

    $connect->exec($sql_content);
    echo "[OK]\n";
    $executed_count++;

  } catch (PDOException $e) {
    echo "[ERROR]\n";
    echo "DETALLE: " . $e->getMessage() . "\n";
    echo "--------------------------------------------------------\n";
    echo "[ERROR] Proceso detenido por fallos críticos.\n";
    exit();
  }
}

echo "--------------------------------------------------------\n";
echo "[OK] Se aplicaron $executed_count archivos SQL.\n\n";
