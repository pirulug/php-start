<?php
// ===============================================
// Visitantes en línea (últimos 5 minutos)
// ===============================================
require_once BASE_DIR . '/core/services/AnalyticsService.php';

$service = new AnalyticsService($connect);
$onlineVisitors = $service->getOnlineVisitorsTable();