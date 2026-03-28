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
    $file = $this->cacheDir . md5($key) . '.cache';
    @file_put_contents($file, json_encode($data));
  }

  public function getDashboardSummary(): array {
    $cacheKey = 'dashboard_summary_v1';
    $cached = $this->getCache($cacheKey, 300); // 5 minutes cache
    if ($cached !== null) {
      // For online users we want fresh data, so we don't cache that part or we append it dynamically
      $cached['totals']['online'] = $this->getOnlineUsersCount();
      $cached['onlineUsers'] = $this->getOnlineUsersList();
      return $cached;
    }

    $data = [
      'totals'         => [
        'visitors' => (int) $this->connect->query("SELECT COUNT(*) FROM visitors")->fetchColumn(),
        'pages'    => (int) $this->connect->query("SELECT COUNT(*) FROM visitor_pages")->fetchColumn(),
        'sessions' => (int) $this->connect->query("SELECT COUNT(*) FROM visitor_sessions")->fetchColumn(),
        'online'   => 0
      ],
      'summary'        => [
        'today'     => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE DATE(visitor_last_visit) = CURDATE()")->fetch(PDO::FETCH_ASSOC),
        'yesterday' => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE DATE(visitor_last_visit) = CURDATE() - INTERVAL 1 DAY")->fetch(PDO::FETCH_ASSOC),
        'thisWeek'  => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEARWEEK(visitor_last_visit,1) = YEARWEEK(CURDATE(),1)")->fetch(PDO::FETCH_ASSOC),
        'lastWeek'  => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEARWEEK(visitor_last_visit,1) = YEARWEEK(CURDATE(),1)-1")->fetch(PDO::FETCH_ASSOC),
        'thisMonth' => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE()) AND MONTH(visitor_last_visit)=MONTH(CURDATE())")->fetch(PDO::FETCH_ASSOC),
        'lastMonth' => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(visitor_last_visit)=MONTH(CURDATE() - INTERVAL 1 MONTH)")->fetch(PDO::FETCH_ASSOC),
        'last7'     => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 7 DAY)")->fetch(PDO::FETCH_ASSOC),
        'last30'    => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 30 DAY)")->fetch(PDO::FETCH_ASSOC),
        'last90'    => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 90 DAY)")->fetch(PDO::FETCH_ASSOC),
        'last6m'    => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 6 MONTH)")->fetch(PDO::FETCH_ASSOC),
        'thisYear'  => $this->connect->query("SELECT COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE())")->fetch(PDO::FETCH_ASSOC),
        'total'     => [
          'visitors' => (int) $this->connect->query("SELECT COUNT(*) FROM visitors")->fetchColumn(), // optimization: query again, wait could use previous variable
          'visits'   => (int) $this->connect->query("SELECT SUM(visitor_total_hits) FROM visitors")->fetchColumn()
        ]
      ],
      'monthDays'      => $this->connect->query("SELECT DAY(visitor_last_visit) AS day, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE()) AND MONTH(visitor_last_visit)=MONTH(CURDATE()) GROUP BY day ORDER BY day")->fetchAll(PDO::FETCH_ASSOC),
      'yearMonths'     => $this->connect->query("SELECT MONTH(visitor_last_visit) AS month, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit)=YEAR(CURDATE()) GROUP BY month ORDER BY month")->fetchAll(PDO::FETCH_ASSOC),
      'lastYears'      => $this->connect->query("SELECT YEAR(visitor_last_visit) AS year, SUM(visitor_total_hits) AS visits FROM visitors WHERE YEAR(visitor_last_visit) >= YEAR(CURDATE()) - 9 GROUP BY year ORDER BY year ASC")->fetchAll(PDO::FETCH_ASSOC),
      'trend'          => $this->connect->query("SELECT DATE(visitor_last_visit) AS date, COUNT(DISTINCT visitor_id) AS visitors, SUM(visitor_total_hits) AS visits FROM visitors WHERE visitor_last_visit >= (NOW() - INTERVAL 30 DAY) GROUP BY DATE(visitor_last_visit) ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC),
      'topPages'       => $this->connect->query("SELECT visitor_page_title AS title, visitor_page_total_views AS views FROM visitor_pages ORDER BY visitor_page_total_views DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC),
      'countries'      => $this->connect->query("SELECT visitor_country AS country, COUNT(*) AS total FROM visitors WHERE visitor_country IS NOT NULL AND visitor_country <> '' GROUP BY country ORDER BY total DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC),
      'recentVisitors' => $this->connect->query("SELECT visitor_ip, visitor_country, visitor_city, visitor_browser, visitor_platform, visitor_last_visit FROM visitors ORDER BY visitor_last_visit DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC),
      'recentSessions' => $this->connect->query("SELECT v.visitor_country, v.visitor_browser, v.visitor_platform, s.visitor_session_start_page, s.visitor_session_start_time FROM visitor_sessions s JOIN visitors v ON s.visitor_session_visitor_id = v.visitor_id ORDER BY s.visitor_session_start_time DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC),
      'browsers'       => $this->connect->query("SELECT visitor_browser AS browser, COUNT(*) AS total FROM visitors WHERE visitor_browser IS NOT NULL AND visitor_browser <> '' GROUP BY browser ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC),
      'platforms'      => $this->connect->query("SELECT visitor_platform AS platform, COUNT(*) AS total FROM visitors WHERE visitor_platform IS NOT NULL AND visitor_platform <> '' GROUP BY platform ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC),
      'devices'        => $this->connect->query("SELECT visitor_device AS device, COUNT(*) AS total FROM visitors WHERE visitor_device IS NOT NULL AND visitor_device <> '' GROUP BY device ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC),
      'referers'       => $this->connect->query("SELECT visitor_referer AS referer, COUNT(*) AS total FROM visitors WHERE visitor_referer IS NOT NULL AND visitor_referer <> '' GROUP BY referer ORDER BY total DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC),
      'byHour'         => $this->connect->query("SELECT DATE_FORMAT(visitor_last_visit, '%H:00') AS hour, COUNT(*) AS visitors FROM visitors WHERE DATE(visitor_last_visit) = CURDATE() GROUP BY hour ORDER BY hour ASC")->fetchAll(PDO::FETCH_ASSOC)
    ];

    $data['totals']['visitors'] = $data['summary']['total']['visitors'];

    $this->setCache($cacheKey, $data);

    // Fresh data
    $data['totals']['online'] = $this->getOnlineUsersCount();
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
    ")->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getViewsStats(string $range): array {
    $cacheKey = 'views_stats_' . $range;
    $cached = $this->getCache($cacheKey, 60); // 1 minute cache
    if ($cached !== null) {
      return $cached;
    }

    $labels     = [];
    $visitorsJS = [];
    $viewsJS    = [];

    if ($range === 'week') {
      $start = new DateTimeImmutable('monday this week');
      $end   = new DateTimeImmutable('sunday this week');

      for ($i = 0; $i < 7; $i++) {
        $d                = $start->modify("+{$i} day");
        $key              = $d->format('Y-m-d');
        $labels[$key]     = $d->format('D d');
        $visitorsJS[$key] = 0;
        $viewsJS[$key]    = 0;
      }

      $rows = $this->connect->query("
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
    } else {
      for ($h = 0; $h < 24; $h++) {
        $label          = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        $labels[$h]     = $label;
        $visitorsJS[$h] = 0;
        $viewsJS[$h]    = 0;
      }

      $rows = $this->connect->query("
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
    }

    $data = [
      'labels' => array_values($labels),
      'visitorsJS' => array_values($visitorsJS),
      'viewsJS' => array_values($viewsJS),
    ];

    $this->setCache($cacheKey, $data);
    return $data;
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
      ORDER BY v.visitor_last_visit DESC
      LIMIT 20
    ")->fetchAll(PDO::FETCH_OBJ);
  }

  public function getVisitorsTable(): array {
    return $this->connect->query("
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
      ORDER BY v.visitor_total_hits DESC
      LIMIT 20
    ")->fetchAll(PDO::FETCH_OBJ);
  }

  public function getMapData(array $filters): array {
    $rangeType = $filters['range_type'] ?? 'day';
    $dateDay   = $filters['date_day'] ?? null;
    $dateFrom  = $filters['date_from'] ?? null;
    $dateTo    = $filters['date_to'] ?? null;
    $hourFrom  = $filters['hour_from'] ?? null;
    $hourTo    = $filters['hour_to'] ?? null;

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

    $rows = $this->connect->query("
      SELECT visitor_country, COUNT(*) AS total
      FROM visitors
      {$whereSql}
      GROUP BY visitor_country
    ")->fetchAll(PDO::FETCH_OBJ);

    $mapData = [];
    foreach ($rows as $r) {
      $mapData[strtoupper($r->visitor_country)] = (int)$r->total;
    }
    
    return $mapData;
  }
}
