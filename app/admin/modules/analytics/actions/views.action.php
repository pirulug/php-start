<?php
require_once BASE_DIR . '/core/services/AnalyticsService.php';

$range   = $_GET['range'] ?? 'day';
$service = new AnalyticsService($connect);

$stats      = $service->getViewsStats($range);
$labels     = $stats['labels'];
$visitorsJS = $stats['visitorsJS'];
$viewsJS    = $stats['viewsJS'];

$lastViews  = $service->getLastViewsTable();