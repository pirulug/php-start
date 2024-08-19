<?php

require_once "../../core.php";

if (!isUserLoggedIn()) {
  header('Location: ' . SITE_URL . '/admin/controllers/login.php');
  exit();
}

if (!$accessControl->hasAccess([0, 1], $_SESSION['user_role'])) {
  header("Location: " . SITE_URL . "/admin/controllers/dashboard.php");
  exit();
}

/* ========== Theme config ========= */
$theme_title = "Perfil Usuario";
$theme_path  = "profile-new";
include BASE_DIR_ADMIN . "/views/user/profile.view.php";
/* ================================= */