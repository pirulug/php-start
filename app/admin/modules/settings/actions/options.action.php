<?php

$query      = "SELECT option_key, option_value FROM options";
$optionsRaw = $connect->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $option_updates = [
    'loader' => $_POST['loader'],
  ];

  // Actualizar cada opción
  foreach ($option_updates as $key => $value) {
    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
    $stmt->execute([
      ':value' => $value,
      ':key'   => $key
    ]);
  }

  $notifier
    ->message('Se actualizó correctamente.')
    ->bootstrap()
    ->success()
    ->add();
  header("Refresh:0");
  exit();
}