<?php

require_once "../../core.php";

$accessControl->check_access([1], SITE_URL . "/404.php");

$stats   = $visitCounter->get_basic_stats();
$daily   = $visitCounter->get_graph_data('daily');
$monthly = $visitCounter->get_graph_data('monthly');
$yearly  = $visitCounter->get_graph_data('yearly');

$page_comparative = $visitCounter->get_page_comparative_daily_data();

$alwaysPage = $visitCounter->get_total_visits_by_page();

$top_ips = $visitCounter->get_top_ips(10);

/* ========== Theme config ========= */
$theme_title = "Estadísticas";
$theme_path  = "statistics";
include BASE_DIR_ADMIN . "/views/settings/statistics.view.php";
/* ================================= */