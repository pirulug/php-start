<?php

class AnalyticsService {
  private $connect;
  private $cacheDir;

  public function __construct(PDO $pdo) {
    $this->connect = $pdo;
    $this->cacheDir = BASE_DIR . '/storage/cache/analytics/';
    if (!is_dir($this->cacheDir)) {
      @mkdir($this->cacheDir, 0755, true);
    }
  }

  private function getCache(string $key, int $ttl) {
    $file = $this->cacheDir . md5($key) . '.cache';
    if (file_exists($file) && (time() - filemtime($file)) < $ttl) {
      $data = @file_get_contents($file);
      if ($data !== false) {
        return json_decode($data, true);
      }
    }
    return null;
  }

  private function setCache(string $key, $data): void {
    if (!is_dir($this->cacheDir)) {
      @mkdir($this->cacheDir, 0755, true);
    }
    $file = $this->cacheDir . md5($key) . '.cache';
    @file_put_contents($file, json_encode($data));
  }

  public function getDashboardSummary(): array {
    $cacheKey = 'dashboard_summary_v3';
    $cached = $this->getCache($cacheKey, 300); // 5 minutes cache
    if ($cached !== null) {
      $cached['totals']['online'] = (int)$this->getOnlineUsersCount();
      $cached['onlineUsers'] = $this->getOnlineUsersList();
      return $cached;
    }

    // 1. Optimized Main Stats using Conditional Aggregation
    $mainStats = $this->connect->query("
      SELECT 
        COUNT(*) AS total_visitors,
        SUM(visitor_total_hits) AS total_visits,
        
        COUNT(CASE WHEN DATE(visitor_last_visit) = CURDATE() THEN 1 END) AS today_visitors,
        SUM(CASE WHEN DATE(visitor_last_visit) = CURDATE() THEN visitor_total_hits ELSE 0 END) AS today_visits,
        
        COUNT(CASE WHEN DATE(visitor_last_visit) = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday_visitors,
        SUM(CASE WHEN DATE(visitor_last_visit) = CURDATE() - INTERVAL 1 DAY THEN visitor_total_hits ELSE 0 END) AS yesterday_visits,
        
        COUNT(CASE WHEN YEARWEEK(visitor_last_visit, 1) = YEARWEEK(CURDATE(), 1) THEN 1 END) AS this_week_visitors,
        SUM(CASE WHEN YEARWEEK(visitor_last_visit, 1) = YEARWEEK(CURDATE(), 1) THEN visitor_total_hits ELSE 0 END) AS this_week_visits,
        
        COUNT(CASE WHEN YEAR(visitor_last_visit) = YEAR(CURDATE()) AND MONTH(visitor_last_visit) = MONTH(CURDATE()) THEN 1 END) AS this_month_visitors,
        SUM(CASE WHEN YEAR(visitor_last_visit) = YEAR(CURDATE()) AND MONTH(visitor_last_visit) = MONTH(CURDATE()) THEN visitor_total_hits ELSE 0 END) AS this_month_visits
      FROM visitors
      WHERE visitor_is_bot = 0
    ")->fetch(PDO::FETCH_OBJ);

    // 2. Optimized KPIs (Bounce Rate & Session Duration)
    $sessionStats = $this->connect->query("
      SELECT 
        COUNT(*) AS total_sessions,
        SUM(CASE WHEN JSON_LENGTH(visitor_session_path) <= 1 THEN 1 ELSE 0 END) AS single_page_sessions,
        AVG(TIMESTAMPDIFF(SECOND, visitor_session_start_time, visitor_session_end_time)) AS avg_duration_seconds
      FROM visitor_sessions
      WHERE visitor_session_start_time >= (NOW() - INTERVAL 30 DAY)
    ")->fetch(PDO::FETCH_OBJ);

    $bounceRate = $sessionStats->total_sessions > 0 
      ? round(($sessionStats->single_page_sessions / $sessionStats->total_sessions) * 100, 2) 
      : 0;

    $avgDuration = round($sessionStats->avg_duration_seconds ?? 0);
    $avgDurationFormatted = sprintf('%02d:%02d', ($avgDuration / 60) % 60, $avgDuration % 60);

    $data = [
      'totals' => [
        'visitors' => (int) $mainStats->total_visitors,
        'pages'    => (int) $this->connect->query("SELECT SUM(visitor_page_total_views) FROM visitor_pages")->fetchColumn(),
        'sessions' => (int) $sessionStats->total_sessions,
        'online'   => 0,
        'bounce_rate' => $bounceRate,
        'avg_duration' => $avgDurationFormatted
      ],
      'summary' => [
        'today'     => ['visitors' => (int)$mainStats->today_visitors, 'visits' => (int)$mainStats->today_visits],
        'yesterday' => ['visitors' => (int)$mainStats->yesterday_visitors, 'visits' => (int)$mainStats->yesterday_visits],
        'thisWeek'  => ['visitors' => (int)$mainStats->this_week_visitors, 'visits' => (int)$mainStats->this_week_visits],
        'thisMonth' => ['visitors' => (int)$mainStats->this_month_visitors, 'visits' => (int)$mainStats->this_month_visits],
        'total'     => ['visitors' => (int)$mainStats->total_visitors, 'visits' => (int)$mainStats->total_visits]
      ],
      'monthDays'  => $this->connect->query("SELECT DAY(visitor_last_visit) AS day, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE()) AND MONTH(visitor_last_visit)=MONTH(CURDATE()) AND visitor_is_bot = 0 GROUP BY day ORDER BY day")->fetchAll(PDO::FETCH_OBJ),
      'yearMonths' => $this->connect->query("SELECT MONTH(visitor_last_visit) AS month, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE()) AND visitor_is_bot = 0 GROUP BY month ORDER BY month")->fetchAll(PDO::FETCH_OBJ),
      'lastYears'  => $this->connect->query("SELECT YEAR(visitor_last_visit) AS year, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit) >= YEAR(CURDATE()) - 9 AND visitor_is_bot = 0 GROUP BY year ORDER BY year ASC")->fetchAll(PDO::FETCH_OBJ),
      'trend'      => $this->connect->query("SELECT DATE(visitor_last_visit) AS date, COUNT(*) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 30 DAY) AND visitor_is_bot = 0 GROUP BY DATE(visitor_last_visit) ORDER BY date ASC")->fetchAll(PDO::FETCH_OBJ),
      'topPages'   => $this->connect->query("SELECT visitor_page_title AS title, visitor_page_total_views AS views FROM visitor_pages ORDER BY visitor_page_total_views DESC LIMIT 5")->fetchAll(PDO::FETCH_OBJ),
      'countries'  => $this->connect->query("SELECT visitor_country AS country, COUNT(*) AS total FROM visitors WHERE visitor_country IS NOT NULL AND visitor_country <> '' AND visitor_is_bot = 0 GROUP BY country ORDER BY total DESC LIMIT 10")->fetchAll(PDO::FETCH_OBJ),
      'recentSessions' => $this->connect->query("SELECT v.visitor_country, v.visitor_browser, v.visitor_platform, s.visitor_session_start_page, s.visitor_session_start_time FROM visitor_sessions s JOIN visitors v ON s.visitor_session_visitor_id = v.visitor_id WHERE v.visitor_is_bot = 0 ORDER BY s.visitor_session_start_time DESC LIMIT 10")->fetchAll(PDO::FETCH_OBJ),
      'browsers'   => $this->connect->query("SELECT visitor_browser AS browser, COUNT(*) AS total FROM visitors WHERE visitor_browser IS NOT NULL AND visitor_browser <> '' AND visitor_is_bot = 0 GROUP BY browser ORDER BY total DESC")->fetchAll(PDO::FETCH_OBJ),
      'platforms'  => $this->connect->query("SELECT visitor_platform AS platform, COUNT(*) AS total FROM visitors WHERE visitor_platform IS NOT NULL AND visitor_platform <> '' AND visitor_is_bot = 0 GROUP BY platform ORDER BY total DESC")->fetchAll(PDO::FETCH_OBJ),
      'devices'    => $this->connect->query("SELECT visitor_device AS device, COUNT(*) AS total FROM visitors WHERE visitor_device IS NOT NULL AND visitor_device <> '' AND visitor_is_bot = 0 GROUP BY device ORDER BY total DESC")->fetchAll(PDO::FETCH_OBJ),
      'referers'   => $this->connect->query("SELECT visitor_referer AS referer, COUNT(*) AS total FROM visitors WHERE visitor_referer IS NOT NULL AND visitor_referer <> '' AND visitor_is_bot = 0 GROUP BY referer ORDER BY total DESC LIMIT 10")->fetchAll(PDO::FETCH_OBJ),
    ];

    $this->setCache($cacheKey, $data);

    // Fresh Online data
    $data['totals']['online'] = (int)$this->getOnlineUsersCount();
    $data['onlineUsers'] = $this->getOnlineUsersList();

    return $data;
  }

  public function getOnlineUsersCount(): int {
    return (int) $this->connect->query("SELECT COUNT(*) FROM visitor_useronlines WHERE visitor_useronline_last_activity > (NOW() - INTERVAL 5 MINUTE)")->fetchColumn();
  }

  public function getOnlineUsersList(): array {
    return $this->connect->query("
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
    ")->fetchAll(PDO::FETCH_OBJ);
  }

  public function getViewsStats(string $range): array {
    $cacheKey = 'views_stats_' . $range . '_v2';
    $cached = $this->getCache($cacheKey, 60);
    if ($cached !== null) {
      return $cached;
    }

    $labels     = [];
    $visitorsJS = [];
    $viewsJS    = [];

    if ($range === 'week') {
      $start = new DateTimeImmutable('monday this week');
      $startTimeStr = $start->format('Y-m-d') . ' 00:00:00';
      
      for ($i = 0; $i < 7; $i++) {
        $d = $start->modify("+{$i} day");
        $key = $d->format('Y-m-d');
        $labels[$key] = $d->format('D d');
        $visitorsJS[$key] = 0;
        $viewsJS[$key] = 0;
      }
      
      $stmt = $this->connect->prepare("SELECT DATE(visitor_last_visit) AS d, COUNT(*) AS visitors, SUM(visitor_total_hits) AS views FROM visitors WHERE visitor_is_bot = 0 AND visitor_last_visit >= :start GROUP BY d");
      $stmt->bindParam(':start', $startTimeStr);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
      
      foreach ($rows as $r) {
        if (isset($visitorsJS[$r->d])) {
          $visitorsJS[$r->d] = (int) $r->visitors;
          $viewsJS[$r->d] = (int) $r->views;
        }
      }
    } else {
      for ($h = 0; $h < 24; $h++) {
        $labels[$h] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        $visitorsJS[$h] = 0;
        $viewsJS[$h] = 0;
      }
      $rows = $this->connect->query("SELECT HOUR(visitor_last_visit) AS h, COUNT(*) AS visitors, SUM(visitor_total_hits) AS views FROM visitors WHERE DATE(visitor_last_visit) = CURDATE() AND visitor_is_bot = 0 GROUP BY h")->fetchAll(PDO::FETCH_OBJ);
      foreach ($rows as $r) {
        $visitorsJS[(int) $r->h] = (int) $r->visitors;
        $viewsJS[(int) $r->h] = (int) $r->views;
      }
    }

    $data = ['labels' => array_values($labels), 'visitorsJS' => array_values($visitorsJS), 'viewsJS' => array_values($viewsJS)];
    $this->setCache($cacheKey, $data);
    return $data;
  }

  public function getVisitorsTable(): array {
    return $this->connect->query("
      SELECT v.*, s.visitor_session_start_page, s.visitor_session_end_page
      FROM visitors v
      LEFT JOIN visitor_sessions s ON s.visitor_session_visitor_id = v.visitor_id
      WHERE v.visitor_is_bot = 0
      ORDER BY v.visitor_last_visit DESC
      LIMIT 100
    ")->fetchAll(PDO::FETCH_OBJ);
  }

  public function getLastViewsTable(): array {
    return $this->connect->query("
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
      WHERE v.visitor_is_bot = 0
      ORDER BY v.visitor_last_visit DESC
      LIMIT 20
    ")->fetchAll(PDO::FETCH_OBJ);
  }

  public function getOnlineVisitorsTable(): array {
    return $this->connect->query("
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
  }

  public function getTopVisitorsTable(): array {
    return $this->connect->query("
      SELECT
        v.visitor_id,
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
      WHERE v.visitor_is_bot = 0
      ORDER BY v.visitor_total_hits DESC
      LIMIT 20
    ")->fetchAll(PDO::FETCH_OBJ);
  }

  public function getMapData(array $filters): array {
    $rangeType = $filters['range_type'] ?? 'day';
    
    $conditions = [
      "visitor_country IS NOT NULL",
      "visitor_country <> ''",
      "visitor_country <> 'Desconocido'",
      "visitor_is_bot = 0"
    ];

    $params = [];

    if ($rangeType === 'day') {
      $conditions[] = "DATE(visitor_last_visit) = CURDATE()";
    } elseif ($rangeType === 'week') {
      $conditions[] = "YEARWEEK(visitor_last_visit, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($rangeType === 'month') {
      $conditions[] = "YEAR(visitor_last_visit) = YEAR(CURDATE()) AND MONTH(visitor_last_visit) = MONTH(CURDATE())";
    } elseif ($rangeType === 'year') {
      $conditions[] = "YEAR(visitor_last_visit) = YEAR(CURDATE())";
    } elseif ($rangeType === 'custom') {
      $dateFrom = $filters['date_from'] ?? null;
      $dateTo   = $filters['date_to'] ?? null;
      if ($dateFrom) {
        $conditions[] = "DATE(visitor_last_visit) >= :from";
        $params[':from'] = $dateFrom;
      }
      if ($dateTo) {
        $conditions[] = "DATE(visitor_last_visit) <= :to";
        $params[':to'] = $dateTo;
      }
    }

    $whereSql = 'WHERE ' . implode(' AND ', $conditions);

    $stmt = $this->connect->prepare("
      SELECT visitor_country, COUNT(*) AS total
      FROM visitors
      {$whereSql}
      GROUP BY visitor_country
    ");

    foreach ($params as $key => &$val) {
      $stmt->bindParam($key, $val);
    }
    
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

    $mapData = [];
    foreach ($rows as $r) {
      $mapData[strtoupper($r->visitor_country)] = (int)$r->total;
    }
    
    return $mapData;
  }
}
