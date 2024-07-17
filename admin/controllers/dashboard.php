<?php

require_once "../core.php";

// Session Manager
$check_access = $sessionManager->checkUserAccess();

/* ========== Theme config ========= */
$theme_title = "Dashboard";
$theme_path  = "dashboard";
// $theme_scripts = ["pages/dashboard.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN_VIEW . "/dashboard.view.php";
/* ================================= */