<?php

require_once "../core.php";

$accessControl->check_access([1, 2], SITE_URL_ADMIN . "/controllers/logout.php");

$query = "SELECT count(*) as total FROM users WHERE user_id <> 1";
$stmt  = $connect->prepare($query);
$stmt->execute();
$count_user = $stmt->fetch(PDO::FETCH_OBJ)->total;

$stats = $visitCounter->get_total_visits();

// print_r(json_encode($_SESSION));
// exit();

/* ========== Theme config ========= */
$theme_title = "Dashboard";
$theme_path  = "dashboard";
include BASE_DIR_ADMIN . "/views/dashboard.view.php";
/* ================================= */