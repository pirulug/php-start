<?php
// =====================================================
// Filtros (GET)
// =====================================================
$rangeType = $_GET['range_type'] ?? 'day'; // day|specific_day|week|month|year|custom
$dateDay   = $_GET['date_day'] ?? null;   // YYYY-MM-DD (para día específico)
$dateFrom  = $_GET['date_from'] ?? null;  // YYYY-MM-DD
$dateTo    = $_GET['date_to'] ?? null;    // YYYY-MM-DD
$hourFrom  = $_GET['hour_from'] ?? null;  // 0-23
$hourTo    = $_GET['hour_to'] ?? null;    // 0-23

$conditions = [
  "visitor_country IS NOT NULL",
  "visitor_country <> ''",
  "visitor_country <> 'Desconocido'"
];

if ($rangeType === 'day') {
  $conditions[] = "DATE(visitor_last_visit) = CURDATE()";
} elseif ($rangeType === 'specific_day') {
  if ($dateDay) {
    $conditions[] = "DATE(visitor_last_visit) = '{$dateDay}'";
  } else {
    $conditions[] = "DATE(visitor_last_visit) = CURDATE()";
  }
} elseif ($rangeType === 'week') {
  $conditions[] = "YEARWEEK(visitor_last_visit,1) = YEARWEEK(CURDATE(),1)";
} elseif ($rangeType === 'month') {
  $conditions[] = "YEAR(visitor_last_visit)=YEAR(CURDATE()) AND MONTH(visitor_last_visit)=MONTH(CURDATE())";
} elseif ($rangeType === 'year') {
  $conditions[] = "YEAR(visitor_last_visit)=YEAR(CURDATE())";
} elseif ($rangeType === 'custom') {
  if ($dateFrom) $conditions[] = "DATE(visitor_last_visit) >= '{$dateFrom}'";
  if ($dateTo)   $conditions[] = "DATE(visitor_last_visit) <= '{$dateTo}'";
  if ($hourFrom !== null && $hourFrom !== '') $conditions[] = "HOUR(visitor_last_visit) >= ".(int)$hourFrom;
  if ($hourTo !== null && $hourTo !== '')     $conditions[] = "HOUR(visitor_last_visit) <= ".(int)$hourTo;
}

$whereSql = 'WHERE ' . implode(' AND ', $conditions);

// =====================================================
// Datos para mapa (por país)
// =====================================================
$rows = $connect->query("
  SELECT visitor_country, COUNT(*) AS total
  FROM visitors
  {$whereSql}
  GROUP BY visitor_country
")->fetchAll(PDO::FETCH_OBJ);

$mapData = [];
foreach ($rows as $r) {
  $mapData[strtoupper($r->visitor_country)] = (int)$r->total;
}