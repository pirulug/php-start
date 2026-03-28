<?php
require_once BASE_DIR . '/core/services/AnalyticsService.php';

$service = new AnalyticsService($connect);
$visitorsTable = $service->getVisitorsTable();