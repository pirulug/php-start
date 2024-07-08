<?php

require 'core.php';

if (isset($_SESSION['user_name'])) {

  $check_access = check_access($connect);

  if ($check_access['user_role'] == 0) {
    add_message("Super Administrador", "success");
    header("Location:" . APP_URL . "/admin/controllers/dashboard.php");
    exit();
  }

  if ($check_access['user_role'] == 1) {
    add_message("Administrador", "success");
    header("Location:" . APP_URL . "/admin/controllers/dashboard.php");
    exit();
  }

} else {
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}
