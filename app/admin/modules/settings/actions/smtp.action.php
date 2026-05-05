<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $smtp_updates = [
    'smtp_host'       => clear_input($_POST['st_smtphost'] ?? ''),
    'smtp_email'      => clear_input($_POST['st_smtpemail'] ?? ''),
    'smtp_password'   => clear_input($_POST['st_smtppassword'] ?? ''),
    'smtp_port'       => clear_input($_POST['st_smtpport'] ?? ''),
    'smtp_encryption' => clear_input($_POST['st_smtpencrypt'] ?? ''),
  ];

  // Guardar usando el helper del dominio (Masivo)
  meta_options_upsert_many($smtp_updates);

  $notifier
    ->message("Configuración SMTP actualizada.")
    ->bootstrap()
    ->success()
    ->add();

  header("Refresh:0");
  exit();
}