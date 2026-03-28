<?php
require_once BASE_DIR . '/core/services/AnalyticsService.php';

$service = new AnalyticsService($connect);
$data = $service->getDashboardSummary();

echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>