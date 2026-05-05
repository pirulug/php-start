<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $option_updates = [
    'site_name'        => clear_input($_POST['st_sitename'] ?? ''),
    'site_description' => clear_input($_POST['st_description'] ?? ''),
  ];

  // Procesar keywords (Tagify envía un JSON)
  $st_keywords_raw  = $_POST['st_keywords'] ?? '[]';
  $st_keywords_json = json_decode($st_keywords_raw, true);

  if (is_array($st_keywords_json)) {
    $keywords                        = array_map(fn($item) => $item['value'], $st_keywords_json);
    $option_updates['site_keywords'] = implode(',', $keywords);
  } else {
    // Fallback por si no es JSON (Tagify desactivado o error)
    $option_updates['site_keywords'] = clear_input($st_keywords_raw);
  }

  // Actualizar cada opción usando patrón seguro UPSERT
  meta_options_upsert_many($option_updates);

  $notifier
    ->message('Ajustes generales actualizados correctamente.')
    ->bootstrap()
    ->success()
    ->add();

  header("Location: " . admin_route('settings/general'));
  exit();
}