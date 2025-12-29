<?php

/******************************
 * VARIABLES Y CONFIGURACIÓN
 ******************************/
$backupDir = BASE_DIR . '/uploads/backups';
if (!is_dir($backupDir))
  mkdir($backupDir, 0777, true);
$action = $_GET['action'] ?? '';

/******************************
 * CREAR RESPALDO
 ******************************/
if ($action === 'backup') {
  $filename = "db-backup-on-" . date('Y-m-d-H-i-s') . ".sql";
  $filepath = "$backupDir/$filename";

  // Exportar estructura y datos (sin mysqldump)
  $tables    = $connect->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
  $sqlScript = "";

  foreach ($tables as $table) {
    // Crear tabla
    $result    = $connect->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
    $sqlScript .= "\n\n" . $result['Create Table'] . ";\n\n";

    // Insertar datos
    $rows = $connect->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
      $values    = array_map(function ($v) use ($connect) {
        return $v === null ? "NULL" : $connect->quote($v);
      }, array_values($row));
      $sqlScript .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
    }
    $sqlScript .= "\n";
  }

  file_put_contents($filepath, $sqlScript);
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit;
}

/******************************
 * RESTAURAR RESPALDO
 ******************************/
if ($action === 'restore' && !empty($_GET['file'])) {
  $file     = basename($_GET['file']);
  $filepath = "$backupDir/$file";

  if (file_exists($filepath)) {
    try {
      // 1. Desactivar temporalmente la verificación de claves foráneas
      $connect->exec("SET FOREIGN_KEY_CHECKS = 0;");

      // 2. Obtener todas las tablas existentes
      $tables = $connect->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

      // 3. Eliminar TODAS las tablas (sin importar relaciones)
      foreach ($tables as $table) {
        try {
          $connect->exec("DROP TABLE IF EXISTS `$table`;");
        } catch (PDOException $e) {
          // Si alguna tabla no puede eliminarse, continuar sin interrumpir el proceso
        }
      }

      // 4. Leer el contenido del respaldo SQL
      $sql = file_get_contents($filepath);

      // 5. Ejecutar el respaldo
      $connect->exec($sql);

      // 6. Reactivar verificación de claves foráneas
      $connect->exec("SET FOREIGN_KEY_CHECKS = 1;");

    } catch (PDOException $e) {
      die("Error durante la restauración: " . $e->getMessage());
    }
  }
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit;
}

/******************************
 * ELIMINAR RESPALDO
 ******************************/
if ($action === 'delete' && !empty($_GET['file'])) {
  $file     = basename($_GET['file']);
  $filepath = "$backupDir/$file";
  if (file_exists($filepath))
    unlink($filepath);
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit;
}

/******************************
 * DESCARGAR RESPALDO
 ******************************/
if ($action === 'download' && !empty($_GET['file'])) {
  $file     = basename($_GET['file']);
  $filepath = "$backupDir/$file";
  if (file_exists($filepath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
  }
}

/******************************
 * LISTAR RESPALDOS
 ******************************/
$files = glob("$backupDir/*.sql");
rsort($files);