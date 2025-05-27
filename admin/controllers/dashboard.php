<?php

require_once "../core.php";

$accessControl->check_access([1,2], SITE_URL_ADMIN . "/controllers/error.php");

/* ========== Theme config ========= */
$theme_title = "Dashboard";
$theme_path  = "dashboard";
include BASE_DIR_ADMIN . "/views/dashboard.view.php";
/* ================================= */