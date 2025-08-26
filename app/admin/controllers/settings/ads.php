<?php

$query = "SELECT * FROM ads";
$ads   = $connect->query($query)->fetchAll(PDO::FETCH_OBJ);

/* ========== Theme config ========= */
$theme_title = "Ads";
$theme_path  = "ads";
include BASE_DIR_ADMIN . "/views/settings/ads.view.php";
/* ================================= */