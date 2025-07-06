<?php

require_once "../../core.php";

$accessControl->check_access([1], SITE_URL . "/404.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $smtp_updates = [
    'smtp_host'       => clear_data($_POST['st_smtphost']),
    'smtp_email'      => clear_data($_POST['st_smtpemail']),
    'smtp_password'   => clear_data($_POST['st_smtppassword']),
    'smtp_port'       => clear_data($_POST['st_smtpport']),
    'smtp_encryption' => clear_data($_POST['st_smtpencrypt']),
  ];

  foreach ($smtp_updates as $key => $value) {
    $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
    $stmt->execute([
      ':value' => $value,
      ':key'   => $key
    ]);
  }

  $messageHandler->addMessage('Se actualizó de manera correcta', 'success');
  header("Refresh:0");
  exit();
}

// Obtener SMTP config de options
$query = "SELECT option_key, option_value FROM options 
          WHERE option_key IN (
            'smtp_host', 'smtp_email', 'smtp_password', 'smtp_port', 'smtp_encryption'
          )";

$optionsRaw = $connect->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);


/* ========== Theme config ========= */
$theme_title = "SMTP";
$theme_path  = "smtp";
include BASE_DIR_ADMIN . "/views/settings/smtp.view.php";
/* ================================= */