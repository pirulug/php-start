<?php
class VisitCounter {
  private $connect;

  public function __construct($connect) {
    $this->connect = $connect;
  }

  // Registrar visita
  public function register_visit($page) {
    $today = date('Y-m-d');
    $query = $this->connect->prepare("SELECT id FROM visits WHERE page = :page AND visit_date = :visit_date");
    $query->execute([':page' => $page, ':visit_date' => $today]);

    if ($query->rowCount() > 0) {
      $this->connect->prepare("UPDATE visits SET visit_count = visit_count + 1 WHERE page = :page AND visit_date = :visit_date")
        ->execute([':page' => $page, ':visit_date' => $today]);
    } else {
      $this->connect->prepare("INSERT INTO visits (page, visit_date) VALUES (:page, :visit_date)")
        ->execute([':page' => $page, ':visit_date' => $today]);
    }
  }

  // Obtener estadísticas básicas
  public function get_basic_stats() {
    $today            = date('Y-m-d');
    $yesterday        = date('Y-m-d', strtotime('-1 day'));
    $week_start       = date('Y-m-d', strtotime('monday this week'));
    $week_end         = date('Y-m-d', strtotime('sunday this week'));
    $last_week_start  = date('Y-m-d', strtotime('monday last week'));
    $last_week_end    = date('Y-m-d', strtotime('sunday last week'));
    $month_start      = date('Y-m-01');
    $last_month_start = date('Y-m-01', strtotime('first day of last month'));
    $last_month_end   = date('Y-m-t', strtotime('last day of last month'));

    return [
      'today'      => $this->get_visit_count($today, $today),
      'yesterday'  => $this->get_visit_count($yesterday, $yesterday),
      'this_week'  => $this->get_visit_count($week_start, $week_end),
      'last_week'  => $this->get_visit_count($last_week_start, $last_week_end),
      'this_month' => $this->get_visit_count($month_start, date('Y-m-d')),
      'last_month' => $this->get_visit_count($last_month_start, $last_month_end),
      'all_time'   => $this->get_visit_count()
    ];
  }

  // Obtener estadísticas diarias, mensuales o anuales
  public function get_graph_data($type) {
    switch ($type) {
      case 'daily':
        $month_start = date('Y-m-01');
        return $this->connect->query("
                    SELECT visit_date, SUM(visit_count) AS total
                    FROM visits
                    WHERE visit_date >= '$month_start'
                    GROUP BY visit_date
                ")->fetchAll(PDO::FETCH_ASSOC);
      case 'monthly':
        $year_start = date('Y-01-01');
        return $this->connect->query("
                    SELECT DATE_FORMAT(visit_date, '%Y-%m') AS month, SUM(visit_count) AS total
                    FROM visits
                    WHERE visit_date >= '$year_start'
                    GROUP BY month
                ")->fetchAll(PDO::FETCH_ASSOC);
      case 'yearly':
        $last_10_years_start = date('Y-m-d', strtotime('-10 years'));
        return $this->connect->query("
                    SELECT YEAR(visit_date) AS year, SUM(visit_count) AS total
                    FROM visits
                    WHERE visit_date >= '$last_10_years_start'
                    GROUP BY year
                ")->fetchAll(PDO::FETCH_ASSOC);
      default:
        return [];
    }
  }

  // Obtener cantidad de visitas en un rango de fechas
  private function get_visit_count($start_date = null, $end_date = null) {
    $query = "SELECT SUM(visit_count) FROM visits";
    if ($start_date && $end_date) {
      $query .= " WHERE visit_date BETWEEN :start_date AND :end_date";
      $stmt  = $this->connect->prepare($query);
      $stmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
    } else {
      $stmt = $this->connect->query($query);
    }
    return $stmt->fetchColumn() ?: 0;
  }

  // Obtener estadísticas diarias para todas las páginas del año
  public function get_page_comparative_daily_data() {
    $year_start = date('Y-01-01');

    // Query para obtener visitas diarias por página en todo el año
    $query = "
      SELECT visit_date, page, SUM(visit_count) AS total
      FROM visits
      WHERE visit_date >= '$year_start'
      GROUP BY visit_date, page
      ORDER BY visit_date ASC
  ";

    $result = $this->connect->query($query)->fetchAll(PDO::FETCH_ASSOC);

    // Organizar los datos para cada página
    $data = [];
    foreach ($result as $row) {
      $data[$row['page']][] = ['date' => $row['visit_date'], 'total' => $row['total']];
    }

    return $data;
  }
}
