<?php

// ===============================================
// Totales básicos
// ===============================================
$totalVisitors = $connect->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
$totalPages    = $connect->query("SELECT COUNT(*) FROM visitor_pages")->fetchColumn();
$totalSessions = $connect->query("SELECT COUNT(*) FROM visitor_sessions")->fetchColumn();
$usersOnline   = $connect->query("
  SELECT COUNT(*)
  FROM visitor_useronlines
  WHERE visitor_useronline_last_activity > (NOW() - INTERVAL 5 MINUTE)
")->fetchColumn();

// ===============================================
// Resumen de tráfico (visitas y visitantes)
// ===============================================
$summary = [
  'today'     => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE DATE(visitor_last_visit) = CURDATE()")->fetch(PDO::FETCH_ASSOC),
  'yesterday' => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE DATE(visitor_last_visit) = CURDATE() - INTERVAL 1 DAY")->fetch(PDO::FETCH_ASSOC),
  'thisWeek'  => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEARWEEK(visitor_last_visit,1) = YEARWEEK(CURDATE(),1)")->fetch(PDO::FETCH_ASSOC),
  'lastWeek'  => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEARWEEK(visitor_last_visit,1) = YEARWEEK(CURDATE(),1)-1")->fetch(PDO::FETCH_ASSOC),
  'thisMonth' => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE()) AND MONTH(visitor_last_visit)=MONTH(CURDATE())")->fetch(PDO::FETCH_ASSOC),
  'lastMonth' => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(visitor_last_visit)=MONTH(CURDATE() - INTERVAL 1 MONTH)")->fetch(PDO::FETCH_ASSOC),
  'last7'     => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 7 DAY)")->fetch(PDO::FETCH_ASSOC),
  'last30'    => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 30 DAY)")->fetch(PDO::FETCH_ASSOC),
  'last90'    => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 90 DAY)")->fetch(PDO::FETCH_ASSOC),
  'last6m'    => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 6 MONTH)")->fetch(PDO::FETCH_ASSOC),
  'thisYear'  => $connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE())")->fetch(PDO::FETCH_ASSOC),
  'total'     => [
    'visitors' => (int) $totalVisitors,
    'visits'   => (int) $connect->query("SELECT SUM(visitor_total_hits) FROM visitors")->fetchColumn()
  ]
];

// ===============================================
// Visitas por día del mes actual
// ===============================================
$monthDays = $connect->query("
  SELECT DAY(visitor_last_visit) AS day, SUM(visitor_total_hits) AS visits
  FROM visitors
  WHERE YEAR(visitor_last_visit)=YEAR(CURDATE())
    AND MONTH(visitor_last_visit)=MONTH(CURDATE())
  GROUP BY day
  ORDER BY day
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Visitas por mes del año actual
// ===============================================
$yearMonths = $connect->query("
  SELECT MONTH(visitor_last_visit) AS month, SUM(visitor_total_hits) AS visits
  FROM visitors
  WHERE YEAR(visitor_last_visit)=YEAR(CURDATE())
  GROUP BY month
  ORDER BY month
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Visitas por año (últimos 10 años)
// ===============================================
$lastYears = $connect->query("
  SELECT YEAR(visitor_last_visit) AS year, SUM(visitor_total_hits) AS visits
  FROM visitors
  WHERE YEAR(visitor_last_visit) >= YEAR(CURDATE()) - 9
  GROUP BY year
  ORDER BY year ASC
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Tendencia de tráfico (últimos 30 días)
// ===============================================
$trafficTrend = $connect->query("
  SELECT 
    DATE(visitor_last_visit) AS date,
    COUNT(DISTINCT visitor_id) AS visitors,
    SUM(visitor_total_hits) AS visits
  FROM visitors
  WHERE visitor_last_visit >= (NOW() - INTERVAL 30 DAY)
  GROUP BY DATE(visitor_last_visit)
  ORDER BY date ASC
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Páginas más vistas
// ===============================================
$topPages = $connect->query("
  SELECT visitor_page_title AS title, visitor_page_total_views AS views
  FROM visitor_pages
  ORDER BY visitor_page_total_views DESC
  LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Visitantes por país (Top 10)
// ===============================================
$countries = $connect->query("
  SELECT visitor_country AS country, COUNT(*) AS total
  FROM visitors
  WHERE visitor_country IS NOT NULL AND visitor_country <> ''
  GROUP BY country
  ORDER BY total DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Últimos visitantes activos
// ===============================================
$recentVisitors = $connect->query("
  SELECT 
    visitor_ip,
    visitor_country,
    visitor_city,
    visitor_browser,
    visitor_platform,
    visitor_last_visit
  FROM visitors
  ORDER BY visitor_last_visit DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Últimas sesiones
// ===============================================
$recentSessions = $connect->query("
  SELECT 
    v.visitor_country,
    v.visitor_browser,
    v.visitor_platform,
    s.visitor_session_start_page,
    s.visitor_session_start_time
  FROM visitor_sessions s
  JOIN visitors v ON s.visitor_session_visitor_id = v.visitor_id
  ORDER BY s.visitor_session_start_time DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Usuarios actualmente en línea (últimos 5 minutos)
// ===============================================
$onlineUsers = $connect->query("
  SELECT 
    u.visitor_useronline_ip,
    p.visitor_page_title,
    u.visitor_useronline_last_activity,
    v.visitor_country,
    v.visitor_browser,
    v.visitor_platform
  FROM visitor_useronlines u
  JOIN visitors v ON u.visitor_useronline_visitor_id = v.visitor_id
  LEFT JOIN visitor_pages p ON u.visitor_useronline_page_id = p.visitor_page_id
  WHERE u.visitor_useronline_last_activity > (NOW() - INTERVAL 5 MINUTE)
  ORDER BY u.visitor_useronline_last_activity DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Uso de navegadores
// ===============================================
$browsers = $connect->query("
  SELECT visitor_browser AS browser, COUNT(*) AS total
  FROM visitors
  WHERE visitor_browser IS NOT NULL AND visitor_browser <> ''
  GROUP BY browser
  ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Sistemas operativos
// ===============================================
$platforms = $connect->query("
  SELECT visitor_platform AS platform, COUNT(*) AS total
  FROM visitors
  WHERE visitor_platform IS NOT NULL AND visitor_platform <> ''
  GROUP BY platform
  ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Dispositivos
// ===============================================
$devices = $connect->query("
  SELECT visitor_device AS device, COUNT(*) AS total
  FROM visitors
  WHERE visitor_device IS NOT NULL AND visitor_device <> ''
  GROUP BY device
  ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Principales referencias
// ===============================================
$referers = $connect->query("
  SELECT visitor_referer AS referer, COUNT(*) AS total
  FROM visitors
  WHERE visitor_referer IS NOT NULL AND visitor_referer <> ''
  GROUP BY referer
  ORDER BY total DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Visitantes por hora (hoy)
// ===============================================
$visitorsByHour = $connect->query("
  SELECT 
    DATE_FORMAT(visitor_last_visit, '%H:00') AS hour,
    COUNT(*) AS visitors
  FROM visitors
  WHERE DATE(visitor_last_visit) = CURDATE()
  GROUP BY hour
  ORDER BY hour ASC
")->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// Respuesta JSON consolidada
// ===============================================
echo json_encode([
  'totals'         => [
    'visitors' => (int) $totalVisitors,
    'pages'    => (int) $totalPages,
    'sessions' => (int) $totalSessions,
    'online'   => (int) $usersOnline
  ],
  'trend'          => $trafficTrend,
  'topPages'       => $topPages,
  'countries'      => $countries,
  'recentVisitors' => $recentVisitors,
  'recentSessions' => $recentSessions,
  'onlineUsers'    => $onlineUsers,
  'browsers'       => $browsers,
  'platforms'      => $platforms,
  'devices'        => $devices,
  'referers'       => $referers,
  'byHour'         => $visitorsByHour,
  'summary'        => $summary,
  'monthDays'      => $monthDays,
  'yearMonths'     => $yearMonths,
  'lastYears'      => $lastYears
], JSON_UNESCAPED_UNICODE);

?>