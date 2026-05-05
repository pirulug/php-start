<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $option_updates = [
    'captcha_enabled'                 => isset($_POST['captcha_enabled']) ? 1 : 0,
    'captcha_type'                    => clear_input($_POST['captcha_type'] ?? 'vanilla'),
    'cloudflare_turnstile_site_key'   => clear_input($_POST['cloudflare_turnstile_site_key'] ?? ''),
    'cloudflare_turnstile_secret_key' => clear_input($_POST['cloudflare_turnstile_secret_key'] ?? ''),
    'google_recaptcha_site_key'       => clear_input($_POST['google_recaptcha_site_key'] ?? ''),
    'google_recaptcha_secret_key'     => clear_input($_POST['google_recaptcha_secret_key'] ?? ''),
  ];

  // Actualizar cada opción usando patrón seguro UPSERT
  foreach ($option_updates as $key => $value) {
    $opt_key = $key;
    $opt_val = $value;

    $stmt = $connect->prepare("INSERT INTO options (option_key, option_value) 
                               VALUES (:key, :val1) 
                               ON DUPLICATE KEY UPDATE option_value = :val2");

    $stmt->bindParam(':key', $opt_key);
    $stmt->bindParam(':val1', $opt_val);
    $stmt->bindParam(':val2', $opt_val);
    $stmt->execute();
  }

  // Refrescar caché de configuración
  $config->refresh();

  $notifier
    ->message("Configuración de seguridad actualizada.")
    ->bootstrap()
    ->success()
    ->add();

  header("Refresh:0");
  exit();
}