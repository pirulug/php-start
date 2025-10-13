<?php

$visitCounter = new VisitCounter($connect);

$stats   = $visitCounter->get_basic_stats();
$daily   = $visitCounter->get_graph_data('daily');
$monthly = $visitCounter->get_graph_data('monthly');
$yearly  = $visitCounter->get_graph_data('yearly');

$page_comparative = $visitCounter->get_page_comparative_daily_data();

$alwaysPage = $visitCounter->get_total_visits_by_page();

$top_ips = $visitCounter->get_top_ips(10);


$theme->render(
  BASE_DIR_ADMIN . "/views/settings/statistics.view.php",
  [
    'theme_title'      => $theme_title,
    'theme_path'       => $theme_path,
    'stats'            => $stats,
    'daily'            => $daily,
    'monthly'          => $monthly,
    'yearly'           => $yearly,
    'page_comparative' => $page_comparative,
    'alwaysPage'       => $alwaysPage,
    'top_ips'          => $top_ips,
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);