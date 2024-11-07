<?php

require 'core.php';

if (isset($_SESSION['signedin'])) {

  if ($_SESSION['signedin'] == true) {

    if ($_SESSION["user_role"] == 1) {
      header("Location: " . SITE_URL . "/admin/controllers/dashboard.php");
      $messageHandler->addMessage("Super Administrador", "success");
      exit();
    } elseif ($_SESSION["user_role"] == 2) {
      header("Location: " . SITE_URL . "/admin/controllers/dashboard.php");
      $messageHandler->addMessage("Administrador", "success");
      exit();
    } else {
      header("Location: " . SITE_URL);
      $messageHandler->addMessage("No eres administrador", "danger");
      exit();
    }

  } else {
    header("Location: " . SITE_URL . "/admin/controllers/login.php");
    // $messageHandler->addMessage("No inició sesión", "danger");
    exit();
  }
} else {
  header("Location: " . SITE_URL . "/admin/controllers/login.php");
  // $messageHandler->addMessage("No inició sesión", "danger");
  exit();
}