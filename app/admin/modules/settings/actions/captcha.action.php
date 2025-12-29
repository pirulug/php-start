<?php

$query      = "SELECT option_key, option_value FROM options";
$optionsRaw = $connect->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

$recaptcha_enabled = isset($optionsRaw['google_recaptcha_enabled']) && $optionsRaw['google_recaptcha_enabled'] == '1';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $option_updates = [
    'google_recaptcha_enabled'    => isset($_POST['google_recaptcha_enabled']) ? 1 : 0,
    'google_recaptcha_site_key'   => clear_data($_POST['google_recaptcha_site_key'] ?? ''),
    'google_recaptcha_secret_key' => clear_data($_POST['google_recaptcha_secret_key'] ?? ''),
  ];

  foreach ($option_updates as $key => $value) {
    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
    $stmt->execute([':value' => $value, ':key' => $key]);
  }

  $notifier
    ->message("Se actualizó de manera correcta")
    ->bootstrap()
    ->success()
    ->add();
  header("Refresh:0");
  exit();
}
