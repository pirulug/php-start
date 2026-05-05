<?php

// La variable $config ya está disponible como instancia de SiteConfig desde el bootstrap

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Recoger todas las redes sociales enviadas
  $social_names      = $_POST['social_names'] ?? [];
  $social_urls       = $_POST['social_urls'] ?? [];
  $social_icons      = $_POST['social_icons'] ?? [];
  $sanitized_socials = [];

  foreach ($social_names as $index => $name) {
    $url  = $social_urls[$index] ?? '';
    $icon = $social_icons[$index] ?? '';

    if (!empty($name) && !empty($url)) {
      $sanitized_socials[] = [
        'name' => clear_input($name),
        'url'  => clear_input($url),
        'icon' => clear_input($icon)
      ];
    }
  }

  $json_val = json_encode($sanitized_socials);

  // Guardar usando el helper del dominio
  meta_options_upsert('site_social', $json_val);

  $notifier
    ->message('Redes sociales actualizadas correctamente.')
    ->bootstrap()
    ->success()
    ->add();

  header("Refresh:0");
  exit();
}