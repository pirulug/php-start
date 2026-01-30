<?php
// ===============================================
// Visitantes en línea (últimos 5 minutos)
// ===============================================
$onlineVisitors = $connect->query("
  SELECT
    u.visitor_useronline_last_activity,
    u.visitor_useronline_referer,
    v.visitor_id,
    v.visitor_country,
    v.visitor_browser,
    v.visitor_platform,
    v.visitor_device,
    p.visitor_page_title
  FROM visitor_useronlines u
  JOIN visitors v
    ON u.visitor_useronline_visitor_id = v.visitor_id
  LEFT JOIN visitor_pages p
    ON u.visitor_useronline_page_id = p.visitor_page_id
  WHERE u.visitor_useronline_last_activity > (NOW() - INTERVAL 5 MINUTE)
  ORDER BY u.visitor_useronline_last_activity DESC
")->fetchAll(PDO::FETCH_OBJ);