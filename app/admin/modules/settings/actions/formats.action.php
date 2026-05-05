<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $option_updates = [
    'site_timezone'           => clear_input($_POST['site_timezone'] ?? 'America/Lima'),
    'date_format'             => clear_input($_POST['date_format'] ?? 'd/m/Y'),
    'time_format'             => clear_input($_POST['time_format'] ?? 'H:i a'),
    'datetime_format'         => clear_input($_POST['datetime_format'] ?? 'd/m/Y - H:i a'),
    'number_decimal_sep'      => clear_input($_POST['number_decimal_sep'] ?? '.'),
    'number_thousand_sep'     => $_POST['number_thousand_sep'] ?? ' ',
    'number_decimals'         => (int) ($_POST['number_decimals'] ?? 2),
    'currency_symbol'         => clear_input($_POST['currency_symbol'] ?? '$'),
    'currency_position'       => clear_input($_POST['currency_position'] ?? 'before'),
    'currency_decimal_sep'    => clear_input($_POST['currency_decimal_sep'] ?? '.'),
    'currency_thousand_sep'   => $_POST['currency_thousand_sep'] ?? ',',
    'currency_decimals'       => (int) ($_POST['currency_decimals'] ?? 2),
  ];

  // Actualizar opciones usando el helper del dominio
  meta_options_upsert_many($option_updates);

  $notifier
    ->message('Se actualizó la configuración regional correctamente.')
    ->bootstrap()
    ->success()
    ->add();

  header("Refresh:0");
  exit();
}