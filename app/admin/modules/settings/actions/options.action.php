<?php

// Obtener todas las opciones usando el estándar obligatorio de objetos
$query = "SELECT option_key, option_value FROM options";
$stmt  = $connect->prepare($query);
$stmt->execute();
$rowsRaw = $stmt->fetchAll(PDO::FETCH_OBJ);

// Convertir a un objeto de configuración para fácil acceso en la acción
$options = new stdClass();
foreach ($rowsRaw as $row) {
  $options->{$row->option_key} = $row->option_value;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Acción: Purgar Caché
  if (isset($_POST['action']) && $_POST['action'] === 'clear_cache') {
    $cacheDir = BASE_DIR . '/storage/cache/';
    $files    = glob($cacheDir . '*.php');
    $count    = 0;
    foreach ($files as $file) {
      if (is_file($file)) {
        @unlink($file);
        $count++;
      }
    }
    $notifier->message("Cache purgada correctamente ($count archivos).")->success()->bootstrap()->add();
    header("Refresh:0");
    exit();
  }

  // Acción: Guardar Opciones
  $option_updates = [
    'loader_admin'         => $_POST['loader_admin'] ?? 'false',
    'loader_home'          => $_POST['loader_home'] ?? 'false',
    'site_maintenance_msg' => clear_data($_POST['maintenance_msg'] ?? 'Estamos trabajando en mejoras. Volvemos pronto.'),
  ];

  // Manejo de Archivo de Mantenimiento
  $maintenanceFile    = BASE_DIR . '/MAINTENANCE';
  $maintenanceEnabled = ($_POST['maintenance_mode'] ?? 'off') === 'on';

  if ($maintenanceEnabled && !file_exists($maintenanceFile)) {
    file_put_contents($maintenanceFile, $option_updates['site_maintenance_msg']);
  } elseif (!$maintenanceEnabled && file_exists($maintenanceFile)) {
    @unlink($maintenanceFile);
  }

  // Actualizar cada opción siguiendo estrictamente los estándares de seguridad
  foreach ($option_updates as $key => $value) {
    // Definimos variables para bindParam (mandatario variables, no literales)
    $optKey   = $key;
    $optValue = $value;

    $stmt = $connect->prepare("INSERT INTO options (option_key, option_value) VALUES (:key1, :val1) ON DUPLICATE KEY UPDATE option_value = :val2");
    
    // Vinculación segura
    $stmt->bindParam(':key1', $optKey);
    $stmt->bindParam(':val1', $optValue);
    $stmt->bindParam(':val2', $optValue);
    
    $stmt->execute();
  }

  $notifier
    ->message('Configuracion de sistema actualizada.')
    ->bootstrap()
    ->success()
    ->add();

  header("Refresh:0");
  exit();
}