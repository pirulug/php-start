<?php

$query      = "SELECT option_key, option_value FROM options";
$optionsRaw = $connect->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $option_updates = [
    'site_name'        => clear_data($_POST['st_sitename']),
    'site_description' => clear_data($_POST['st_description']),
    'facebook'         => clear_data($_POST['st_facebook']),
    'twitter'          => clear_data($_POST['st_twitter']),
    'instagram'        => clear_data($_POST['st_instagram']),
    'youtube'          => clear_data($_POST['st_youtube']),
  ];

  // Procesar keywords
  $st_keywords = json_decode($_POST['st_keywords'], true);
  if (is_array($st_keywords)) {
    $keywords                        = array_map(fn($item) => $item['value'], $st_keywords);
    $option_updates['site_keywords'] = implode(',', $keywords);
  }

  // Actualizar cada opción
  foreach ($option_updates as $key => $value) {
    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
    $stmt->execute([
      ':value' => $value,
      ':key'   => $key
    ]);
  }

  // $notifier->add('Se actualizó correctamente.', 'success');
  $notifier
    ->message('Se actualizó correctamente.')
    ->bootstrap()
    ->success()
    ->add();
  header("Refresh:0");
  exit();
}