<?php
// ===============================================
// Top de visitantes (ordenado por vistas totales)
// ===============================================
require_once BASE_DIR . '/core/services/AnalyticsService.php';

$service = new AnalyticsService($connect);
$topVisitors = $service->getTopVisitorsTable();
?>
