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
  $option_updates = [
    'google_analytics_id'   => clear_data($_POST['ga_id']  ?? ''),
    'meta_pixel_id'         => clear_data($_POST['meta_id'] ?? ''),
    'google_search_console' => clear_data($_POST['gsc_id'] ?? ''),
  ];

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
    ->message('Configuracion de tracking actualizada.')
    ->bootstrap()
    ->success()
    ->add();

  header("Refresh:0");
  exit();
}
