<?php

require_once "../../core.php";

// Session Manager
$check_access = $sessionManager->checkUserAccess();

/* ========== Theme config ========= */
$theme_title = "General";
$theme_path  = "general";
// $theme_scripts = ["pages/dashboard.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN_VIEW . "/settings/general.view.php";
/* ================================= */