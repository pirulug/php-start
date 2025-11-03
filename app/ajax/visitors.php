<?php
// header('Content-Type: application/json');

// $connect = new PDO("mysql:host=localhost;dbname=tu_basedatos;charset=utf8mb4", "usuario", "password", [
//   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
// ]);

// Totales
$totalVisitors = $connect->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
$totalPages    = $connect->query("SELECT COUNT(*) FROM visitor_pages")->fetchColumn();
$totalSessions = $connect->query("SELECT COUNT(*) FROM visitor_sessions")->fetchColumn();
$usersOnline   = $connect->query("SELECT COUNT(*) FROM visitor_useronline WHERE visitor_useronline_last_activity > (NOW() - INTERVAL 5 MINUTE)")->fetchColumn();

// Top páginas
$topPages = $connect->query("
  SELECT visitor_pages_title AS title, visitor_pages_total_views AS views
  FROM visitor_pages ORDER BY views DESC LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Países
$countries = $connect->query("
  SELECT visitor_country AS country, COUNT(*) AS total
  FROM visitors
  WHERE visitor_country IS NOT NULL AND visitor_country <> ''
  GROUP BY country ORDER BY total DESC LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Últimas sesiones
$recentSessions = $connect->query("
  SELECT v.visitor_country, v.visitor_browser, v.visitor_platform,
         s.visitor_sessions_start_page, s.visitor_sessions_start_time
  FROM visitor_sessions s
  JOIN visitors v ON s.visitor_sessions_visitor_id = v.visitor_id
  ORDER BY s.visitor_sessions_start_time DESC LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Usuarios online
$onlineUsers = $connect->query("
  SELECT u.visitor_useronline_ip, p.visitor_pages_title, u.visitor_useronline_last_activity
  FROM visitor_useronline u
  LEFT JOIN visitor_pages p ON u.visitor_useronline_page_id = p.visitor_pages_id
  WHERE u.visitor_useronline_last_activity > (NOW() - INTERVAL 5 MINUTE)
  ORDER BY u.visitor_useronline_last_activity DESC
")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
  'totals' => [
    'visitors' => (int)$totalVisitors,
    'pages' => (int)$totalPages,
    'sessions' => (int)$totalSessions,
    'online' => (int)$usersOnline
  ],
  'topPages' => $topPages,
  'countries' => $countries,
  'recentSessions' => $recentSessions,
  'onlineUsers' => $onlineUsers
]);
