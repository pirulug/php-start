<?php

require_once "../../core.php";

if (!isUserLoggedIn()) {
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

if (!$accessControl->hasAccess([0], $_SESSION['user_role'])) {
  header("Location: " . APP_URL . "/admin/controllers/dashboard.php");
  exit();
}

$query = "SELECT * FROM ads";
$ads   = $connect->query($query)->fetchAll(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "Ads";
$theme_path  = "ads";
include BASE_DIR_ADMIN . "/views/settings/ads.view.php";
/* ================================= */