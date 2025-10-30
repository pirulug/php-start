<?php

$query      = "SELECT option_key, option_value FROM options";
$optionsRaw = $connect->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $option_updates = [
    'site_timezone'   => clear_data($_POST['site_timezone']),
    'date_format'     => clear_data($_POST['date_format']),
    'time_format'     => clear_data($_POST['time_format']),
    'datetime_format' => clear_data($_POST['datetime_format']),
  ];

  // Actualizar cada opción
  foreach ($option_updates as $key => $value) {
    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
    $stmt->execute([
      ':value' => $value,
      ':key'   => $key
    ]);
  }

  $notifier->add('Se actualizó correctamente.', 'success');
  header("Refresh:0");
  exit();
}