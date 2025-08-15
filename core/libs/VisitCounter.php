<?php

class VisitCounter {
  private $connect;

  public function __construct($connect) {
    $this->connect = $connect;
  }

  public function register_visit($page) {
    $ip      = $this->get_client_ip();
    $browser = $this->get_browser();
    $os      = $this->get_os();
    $country = $this->get_country_by_ip($ip);

    // Verificar si ya hay una visita en los últimos 30 minutos para esta IP y página
    $stmt = $this->connect->prepare("SELECT 1 FROM visits
        WHERE visit_page = ?
          AND visit_ip = ?
          AND visit_date >= NOW() - INTERVAL 30 MINUTE
        LIMIT 1");
    $stmt->execute([$page, $ip]);

    // Si ya existe, no registrar otra visita
    if ($stmt->fetch()) {
      return;
    }


    $stmt = $this->connect->prepare("INSERT INTO visits (visit_page, visit_ip, visit_country, visit_browser, visit_os)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$page, $ip, $country, $browser, $os]);
  }

  private function get_client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
      return $_SERVER['REMOTE_ADDR'];
    }
  }

  private function get_country_by_ip($ip) {
    return 'Desconocido';
  }

  private function get_browser() {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/firefox/i', $ua))
      return 'Firefox';
    if (preg_match('/chrome/i', $ua))
      return 'Chrome';
    if (preg_match('/safari/i', $ua))
      return 'Safari';
    if (preg_match('/opera|opr/i', $ua))
      return 'Opera';
    if (preg_match('/msie|trident/i', $ua))
      return 'Internet Explorer';
    if (preg_match('/edge/i', $ua))
      return 'Edge';
    return 'Desconocido';
  }

  private function get_os() {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/windows nt 10/i', $ua))
      return 'Windows 10';
    if (preg_match('/windows nt 6.1/i', $ua))
      return 'Windows 7';
    if (preg_match('/macintosh|mac os x/i', $ua))
      return 'Mac OS X';
    if (preg_match('/linux/i', $ua))
      return 'Linux';
    if (preg_match('/android/i', $ua))
      return 'Android';
    if (preg_match('/iphone/i', $ua))
      return 'iPhone';
    return 'Desconocido';
  }

  public function get_total_visits($page = null) {
    if ($page) {
      $stmt = $this->connect->prepare("SELECT COUNT(*) FROM visits WHERE visit_page = ?");
      $stmt->execute([$page]);
    } else {
      $stmt = $this->connect->query("SELECT COUNT(*) FROM visits");
    }
    return $stmt->fetchColumn();
  }

  public function get_top_countries($limit = 10) {
    $stmt = $this->connect->prepare("SELECT visit_country, COUNT(*) as total
      FROM visits
      GROUP BY visit_country
      ORDER BY total DESC
      LIMIT :limit
    ");
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_basic_stats() {
    $today      = $this->get_count_by_period('DAY', 0);
    $yesterday  = $this->get_count_by_period('DAY', 1);
    $this_week  = $this->get_count_by_period('WEEK', 0);
    $last_week  = $this->get_count_by_period('WEEK', 1);
    $this_month = $this->get_count_by_period('MONTH', 0);
    $last_month = $this->get_count_by_period('MONTH', 1);
    $all_time   = $this->get_total_visits();

    return compact('today', 'yesterday', 'this_week', 'last_week', 'this_month', 'last_month', 'all_time');
  }

  private function get_count_by_period($period, $offset = 0) {
    $query = match ($period) {
      'DAY' => "SELECT COUNT(*) FROM visits WHERE DATE(visit_date) = CURDATE() - INTERVAL :offset DAY",
      'WEEK' => "SELECT COUNT(*) FROM visits WHERE YEARWEEK(visit_date, 1) = YEARWEEK(CURDATE() - INTERVAL :offset WEEK, 1)",
      'MONTH' => "SELECT COUNT(*) FROM visits WHERE MONTH(visit_date) = MONTH(CURDATE() - INTERVAL :offset MONTH) AND YEAR(visit_date) = YEAR(CURDATE() - INTERVAL :offset MONTH)",
      default => ""
    };

    $stmt = $this->connect->prepare($query);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return (int) $stmt->fetchColumn();
  }

  public function get_graph_data($type = 'daily') {
    return match ($type) {
      'daily' => $this->get_daily_visits(),
      'monthly' => $this->get_monthly_visits(),
      'yearly' => $this->get_yearly_visits(),
      default => [],
    };
  }

  public function get_daily_visits() {
    $month_start = date('Y-m-01');
    $month_end   = date('Y-m-t');

    $stmt = $this->connect->prepare("SELECT DATE(visit_date) AS visit_day, COUNT(*) AS visit_count
        FROM visits
        WHERE visit_date BETWEEN :start AND :end
        GROUP BY visit_day");
    $stmt->execute(['start' => $month_start, 'end' => $month_end]);
    $visits_raw = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['2025-07-01' => 5, ...]

    $result   = [];
    $start    = new DateTime($month_start);
    $end      = new DateTime($month_end);
    $interval = new DateInterval('P1D');
    $period   = new DatePeriod($start, $interval, $end->modify('+1 day'));

    foreach ($period as $date) {
      $full_date  = $date->format('Y-m-d');
      $day_number = $date->format('d');

      $result[] = [
        'dia'     => (int) $day_number,
        'visitas' => isset($visits_raw[$full_date]) ? (int) $visits_raw[$full_date] : 0,
      ];
    }

    return $result;
  }

  public function get_monthly_visits() {
    $months = [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    $data   = array_fill_keys($months, 0);

    $stmt = $this->connect->prepare("
    SELECT MONTH(visit_date) as month, COUNT(*) as total
    FROM visits
    WHERE YEAR(visit_date) = YEAR(CURDATE())
    GROUP BY month
  ");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $monthName        = $months[$row['month'] - 1];
      $data[$monthName] = (int) $row['total'];
    }

    return $data;
  }

  public function get_yearly_visits() {
    $currentYear = (int) date('Y');
    $years       = range($currentYear - 9, $currentYear);
    $data        = array_fill_keys($years, 0);

    $stmt = $this->connect->prepare("
    SELECT YEAR(visit_date) as year, COUNT(*) as total
    FROM visits
    WHERE YEAR(visit_date) BETWEEN :start AND :end
    GROUP BY year
  ");
    $stmt->bindValue(':start', $currentYear - 9, PDO::PARAM_INT);
    $stmt->bindValue(':end', $currentYear, PDO::PARAM_INT);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data[(int) $row['year']] = (int) $row['total'];
    }

    return $data;
  }

  public function get_page_comparative_daily_data($limit = 7) {
    // 1. Fechas de lunes a domingo de esta semana
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));

    // 2. Obtener las páginas más visitadas de la semana
    $stmt = $this->connect->prepare("
        SELECT visit_page, COUNT(*) AS total
        FROM visits
        WHERE DATE(visit_date) BETWEEN :monday AND :sunday
        GROUP BY visit_page
        ORDER BY total DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':monday', $monday);
    $stmt->bindValue(':sunday', $sunday);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    $top_pages = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($top_pages))
      return [];

    // 3. Obtener visitas por día para esas páginas
    $in     = str_repeat('?,', count($top_pages) - 1) . '?';
    $stmt2  = $this->connect->prepare("
        SELECT visit_page, DATE(visit_date) as date, COUNT(*) as total
        FROM visits
        WHERE DATE(visit_date) BETWEEN ? AND ?
        AND visit_page IN ($in)
        GROUP BY visit_page, date
    ");
    $params = array_merge([$monday, $sunday], $top_pages);
    $stmt2->execute($params);

    $rawData = [];
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $rawData[$row['visit_page']][$row['date']] = (int) $row['total'];
    }

    // 4. Generar los días de la semana con nombre
    $dias_semana = [
      'Monday'    => 'Lunes',
      'Tuesday'   => 'Martes',
      'Wednesday' => 'Miércoles',
      'Thursday'  => 'Jueves',
      'Friday'    => 'Viernes',
      'Saturday'  => 'Sábado',
      'Sunday'    => 'Domingo',
    ];

    $result = [];
    $dates  = [];
    $dt     = new DateTime($monday);
    while ($dt->format('Y-m-d') <= $sunday) {
      $full_date = $dt->format('Y-m-d');
      $dia_texto = $dias_semana[$dt->format('l')]; // l = textual en inglés
      $dates[]   = [
        'fecha' => $full_date,
        'dia'   => $dia_texto
      ];
      $dt->modify('+1 day');
    }

    // 5. Construir el resultado por página
    foreach ($top_pages as $page) {
      $pageData = [];
      foreach ($dates as $info) {
        $visitas    = $rawData[$page][$info['fecha']] ?? 0;
        $pageData[] = [
          'dia'     => $info['dia'], // Lunes, Martes, etc.
          'visitas' => $visitas
        ];
      }
      $result[$page] = $pageData;
    }

    return $result;
  }

  public function get_total_visits_by_page() {
    $stmt = $this->connect->query("SELECT visit_page, COUNT(*) as total FROM visits GROUP BY visit_page ORDER BY total DESC");
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data[$row['visit_page']] = (int) $row['total'];
    }
    return $data;
  }

  public function get_top_ips($limit = 10) {
    $stmt = $this->connect->prepare("SELECT visit_ip as ip_address, COUNT(*) as total_visits
    FROM visits
    GROUP BY visit_ip
    ORDER BY total_visits DESC
    LIMIT :limit");
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

}
