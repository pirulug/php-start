<?php

require_once "../core.php";

$check_access = check_access($connect);

if (!isset($_SESSION['user_name'])) {
  add_message("no inició session", "danger");
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

if ($check_access['user_role'] != 1 && $check_access['user_role'] != 0) {
  add_message("No eres administrador", "danger");
  header('Location: ' . APP_URL . '/');
  exit();
} 

/* ========== Theme config ========= */
$theme_title = "Dashboard";
$theme_path  = "dashboard";
// $theme_scripts = ["pages/dashboard.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN_VIEW . "/dashboard.view.php";
/* ================================= */