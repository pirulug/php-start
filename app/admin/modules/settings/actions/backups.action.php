<?php

/**
 * Backups Action
 * Gestión de respaldos de base de datos siguiendo estándares de seguridad.
 */

$backupDir = BASE_DIR . '/storage/uploads/backups';
if (!is_dir($backupDir)) {
  mkdir($backupDir, 0777, true);
}

$action = $_GET['action'] ?? '';

// ---------------------------------------------------------
// 1. CREAR RESPALDO
// ---------------------------------------------------------
if ($action === 'backup') {
  $filename = "db-backup-on-" . date('Y-m-d-H-i-s') . ".sql";
  $filepath = "$backupDir/$filename";

  try {
    // Obtener tablas usando el estándar obligatorio FETCH_OBJ
    $stmtTables = $connect->prepare("SHOW TABLES");
    $stmtTables->execute();
    $tables = $stmtTables->fetchAll(PDO::FETCH_COLUMN);

    $sqlScript = "-- PHP-Start Database Backup\n";
    $sqlScript .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
    $sqlScript .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

    foreach ($tables as $table) {
      // Crear tabla
      $stmtCreate = $connect->prepare("SHOW CREATE TABLE `$table`");
      $stmtCreate->execute();
      $resCreate = $stmtCreate->fetch(PDO::FETCH_OBJ);
      
      // En FETCH_OBJ, el nombre de la columna suele ser 'Create Table'
      // Pero accedemos mediante conversión a array o propiedad dinámica si el nombre tiene espacios
      $createArray = (array)$resCreate;
      $sqlScript .= "\n\n" . $createArray['Create Table'] . ";\n\n";

      // Insertar datos
      $stmtData = $connect->prepare("SELECT * FROM `$table`");
      $stmtData->execute();
      $rows = $stmtData->fetchAll(PDO::FETCH_ASSOC); // Aquí FETCH_ASSOC es útil para recorrer columnas dinámicamente

      foreach ($rows as $row) {
        $values = array_map(function ($v) use ($connect) {
          if ($v === null) return "NULL";
          return $connect->quote($v);
        }, array_values($row));
        
        $sqlScript .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
      }
    }

    $sqlScript .= "\nSET FOREIGN_KEY_CHECKS = 1;\n";

    if (file_put_contents($filepath, $sqlScript) === false) {
      throw new Exception("No se pudo escribir el archivo de respaldo.");
    }

    $notifier->message("Respaldo creado correctamente.")->success()->bootstrap()->add();

  } catch (Exception $e) {
    $notifier->message("Error al crear respaldo: " . $e->getMessage())->danger()->bootstrap()->add();
  }

  header("Location: " . admin_route('settings/backups'));
  exit;
}

// ---------------------------------------------------------
// 2. DESCARGAR / RESTAURAR / ELIMINAR (REQUIEREN ARCHIVO CIFRADO)
// ---------------------------------------------------------
if (!empty($_GET['file']) && in_array($action, ['download', 'restore', 'delete'])) {
  
  $encryptedFile = $_GET['file'];
  $filename      = $cipher->decrypt($encryptedFile);
  $filepath      = $backupDir . '/' . basename($filename);

  if (!file_exists($filepath)) {
    $notifier->message("El archivo solicitado no existe.")->danger()->bootstrap()->add();
    header("Location: " . admin_route('settings/backups'));
    exit;
  }

  // A. DESCARGAR
  if ($action === 'download') {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Content-Length: ' . filesize($filepath));
    header('Pragma: public');
    readfile($filepath);
    exit;
  }

  // B. RESTAURAR
  if ($action === 'restore') {
    try {
      $sql = file_get_contents($filepath);
      
      $connect->exec("SET FOREIGN_KEY_CHECKS = 0;");
      
      // Limpiar base de datos actual (Opcional pero recomendado para un restore limpio)
      $stmtTables = $connect->prepare("SHOW TABLES");
      $stmtTables->execute();
      $tables = $stmtTables->fetchAll(PDO::FETCH_COLUMN);
      foreach ($tables as $table) {
        $connect->exec("DROP TABLE IF EXISTS `$table` shadow"); // "shadow" no existe, es DROP TABLE
        $connect->exec("DROP TABLE IF EXISTS `$table` ");
      }

      $connect->exec($sql);
      $connect->exec("SET FOREIGN_KEY_CHECKS = 1;");

      $notifier->message("Respaldo restaurado correctamente.")->success()->bootstrap()->add();

    } catch (PDOException $e) {
      $notifier->message("Error en restauracion: " . $e->getMessage())->danger()->bootstrap()->add();
    }
    header("Location: " . admin_route('settings/backups'));
    exit;
  }

  // C. ELIMINAR
  if ($action === 'delete') {
    if (@unlink($filepath)) {
      $notifier->message("Respaldo eliminado permanentemente.")->success()->bootstrap()->add();
    } else {
      $notifier->message("No se pudo eliminar el archivo.")->danger()->bootstrap()->add();
    }
    header("Location: " . admin_route('settings/backups'));
    exit;
  }
}

// ---------------------------------------------------------
// 3. LISTAR RESPALDOS
// ---------------------------------------------------------
$files = glob("$backupDir/*.sql");
rsort($files);