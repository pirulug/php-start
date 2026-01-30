<?php

$visitorsTable = $connect->query("
  SELECT
    v.visitor_id,
    v.visitor_last_visit,
    v.visitor_country,
    v.visitor_browser,
    v.visitor_platform,
    v.visitor_device,
    v.visitor_referer,
    v.visitor_total_hits,
    s.visitor_session_start_page,
    s.visitor_session_end_page
  FROM visitors v
  LEFT JOIN visitor_sessions s
    ON s.visitor_session_visitor_id = v.visitor_id
  ORDER BY v.visitor_last_visit DESC
  LIMIT 50
")->fetchAll(PDO::FETCH_OBJ);