<?php

require_once "../../core.php";

$accessControl->require_login(SITE_URL_ADMIN . "/controllers/login.php");
$accessControl->check_access([1], SITE_URL . "/404.php");

$query = "SELECT * FROM ads";
$ads   = $connect->query($query)->fetchAll(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "Ads";
$theme_path  = "ads";
include BASE_DIR_ADMIN . "/views/settings/ads.view.php";
/* ================================= */