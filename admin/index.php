<?php

require 'core.php';

if (isset($_SESSION['signedin'])) {

  if ($_SESSION['signedin'] == true) {

    if ($_SESSION["user_role"] == 0) {
      header("Location: " . SITE_URL . "/admin/controllers/dashboard.php");
      add_message("Super Administrador", "success");
      exit();
    } elseif ($_SESSION["user_role"] == 1) {
      header("Location: " . SITE_URL . "/admin/controllers/dashboard.php");
      add_message("Administrador", "success");
      exit();
    } else {
      header("Location: " . SITE_URL);
      add_message("No eres administrador", "danger");
      exit();
    }

  } else {
    header("Location: " . SITE_URL . "/admin/controllers/login.php");
    // add_message("No inició sesión", "danger");
    exit();
  }
} else {
  header("Location: " . SITE_URL . "/admin/controllers/login.php");
  // add_message("No inició sesión", "danger");
  exit();
}