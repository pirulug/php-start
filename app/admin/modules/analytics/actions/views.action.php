<?php
// ===============================================
// Parámetro de rango (day | week)
// ===============================================
$range      = $_GET['range'] ?? 'day';
$labels     = [];
$visitorsJS = [];
$viewsJS    = [];

if ($range === 'week') {
  // -------------------------------
  // Semana actual (Lun–Dom)
  // -------------------------------
  $start = new DateTimeImmutable('monday this week');
  $end   = new DateTimeImmutable('sunday this week');

  for ($i = 0; $i < 7; $i++) {
    $d                = $start->modify("+{$i} day");
    $key              = $d->format('Y-m-d');
    $labels[$key]     = $d->format('D d');
    $visitorsJS[$key] = 0;
    $viewsJS[$key]    = 0;
  }

  $rows = $connect->query("
    SELECT
      DATE(visitor_last_visit) AS d,
      COUNT(DISTINCT visitor_id) AS visitors,
      SUM(visitor_total_hits) AS views
    FROM visitors
    WHERE visitor_last_visit BETWEEN '{$start->format('Y-m-d')} 00:00:00'
                                 AND '{$end->format('Y-m-d')} 23:59:59'
    GROUP BY d
  ")->fetchAll(PDO::FETCH_OBJ);

  foreach ($rows as $r) {
    if (isset($visitorsJS[$r->d])) {
      $visitorsJS[$r->d] = (int) $r->visitors;
      $viewsJS[$r->d]    = (int) $r->views;
    }
  }

  $labels     = array_values($labels);
  $visitorsJS = array_values($visitorsJS);
  $viewsJS    = array_values($viewsJS);

} else {
  // -------------------------------
  // Día actual por hora (00–23)
  // -------------------------------
  for ($h = 0; $h < 24; $h++) {
    $label          = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
    $labels[$h]     = $label;
    $visitorsJS[$h] = 0;
    $viewsJS[$h]    = 0;
  }

  $rows = $connect->query("
    SELECT
      HOUR(visitor_last_visit) AS h,
      COUNT(DISTINCT visitor_id) AS visitors,
      SUM(visitor_total_hits) AS views
    FROM visitors
    WHERE DATE(visitor_last_visit) = CURDATE()
    GROUP BY h
  ")->fetchAll(PDO::FETCH_OBJ);

  foreach ($rows as $r) {
    $visitorsJS[(int) $r->h] = (int) $r->visitors;
    $viewsJS[(int) $r->h]    = (int) $r->views;
  }

  $labels     = array_values($labels);
  $visitorsJS = array_values($visitorsJS);
  $viewsJS    = array_values($viewsJS);
}

// ===============================================
// Últimas vistas (tabla)
// ===============================================
$lastViews = $connect->query("
  SELECT
    v.visitor_last_visit,
    v.visitor_country,
    v.visitor_browser,
    v.visitor_platform,
    v.visitor_device,
    v.visitor_referer,
    v.visitor_total_hits,
    s.visitor_session_start_page AS page
  FROM visitors v
  LEFT JOIN visitor_sessions s
    ON s.visitor_session_visitor_id = v.visitor_id
  ORDER BY v.visitor_last_visit DESC
  LIMIT 20
")->fetchAll(PDO::FETCH_OBJ);