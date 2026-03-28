<?php
// =====================================================
// Filtros (GET)
// =====================================================
require_once BASE_DIR . '/core/services/AnalyticsService.php';

$rangeType = $_GET['range_type'] ?? 'day';
$dateDay   = $_GET['date_day'] ?? null;
$dateFrom  = $_GET['date_from'] ?? null;
$dateTo    = $_GET['date_to'] ?? null;
$hourFrom  = $_GET['hour_from'] ?? null;
$hourTo    = $_GET['hour_to'] ?? null;

$service = new AnalyticsService($connect);
$mapData = $service->getMapData($_GET);