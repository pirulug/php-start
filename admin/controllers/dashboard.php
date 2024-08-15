<?php

require_once "../core.php";

if (empty($_SESSION['user_id'])) {
  header("Location: " . APP_URL . "/admin/controllers/login.php");
  exit();
}

if (!$accessControl->hasAccess([0, 1], $_SESSION['user_role'])) {
  header("Location: " . APP_URL);
  exit();
}

/* ========== Theme config ========= */
$theme_title = "Dashboard";
$theme_path  = "dashboard";
// $theme_scripts = ["pages/dashboard.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN_VIEW . "/dashboard.view.php";
/* ================================= */