<?php
class VisitCounter {
  private $connect;

  public function __construct($connect) {
    $this->connect = $connect;
  }

  // Registrar visita
  public function register_visit($page) {
    $today = date('Y-m-d');
    $ip    = $this->get_client_ip();

    // Verifica si ya existe una visita para esa página y fecha
    $query = $this->connect->prepare("SELECT id FROM visits WHERE page = :page AND visit_date = :visit_date");
    $query->execute([':page' => $page, ':visit_date' => $today]);

    if ($query->rowCount() > 0) {
      $visit_id = $query->fetchColumn();

      $this->connect->prepare("UPDATE visits SET visit_count = visit_count + 1 WHERE id = :id")
        ->execute([':id' => $visit_id]);
    } else {
      $this->connect->prepare("INSERT INTO visits (page, visit_date) VALUES (:page, :visit_date)")
        ->execute([':page' => $page, ':visit_date' => $today]);

      $visit_id = $this->connect->lastInsertId();
    }

    // Registrar IP
    $stmt = $this->connect->prepare("INSERT INTO visit_ips (visit_id, ip_address) VALUES (:visit_id, :ip)");
    $stmt->execute([':visit_id' => $visit_id, ':ip' => $ip]);
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
        // Datos por Día (mes actual)
        $month_start = date('Y-m-01');
        $month_end = date('Y-m-t'); // Último día del mes
        $dates = [];

        // Generar todas las fechas del mes actual
        $start = new DateTime($month_start);
        $end = new DateTime($month_end);
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($start, $interval, $end->modify('+1 day'));

        foreach ($daterange as $date) {
          $dates[$date->format('j')] = "0";
        }

        // Consulta los datos existentes
        $daily = $this->connect->query("SELECT visit_date, SUM(visit_count) AS total
                                        FROM visits
                                        WHERE visit_date >= '$month_start'
                                        AND visit_date <= '$month_end'
                                        GROUP BY visit_date
                                    ")->fetchAll(PDO::FETCH_ASSOC);

        // Combina los datos existentes con las fechas generadas
        foreach ($daily as $row) {
          $day         = date('j', strtotime($row['visit_date']));
          $dates[$day] = $row['total'];
        }

        return $dates;

      case 'monthly':
        // Datos por Mes (solo meses del año actual)
        $year_start = date('Y-01-01');
        $year_end = date('Y-12-31'); // Último día del año

        $monthNames = [
          1  => 'Enero',
          2  => 'Febrero',
          3  => 'Marzo',
          4  => 'Abril',
          5  => 'Mayo',
          6  => 'Junio',
          7  => 'Julio',
          8  => 'Agosto',
          9  => 'Septiembre',
          10 => 'Octubre',
          11 => 'Noviembre',
          12 => 'Diciembre'
        ];

        // Inicializamos un array para los meses del año
        $months = [];
        foreach ($monthNames as $key => $month) {
          $months[$month] = "0"; // Inicializamos todos los meses con 0
        }

        // Consulta los datos existentes
        $monthly = $this->connect->query("SELECT DATE_FORMAT(visit_date, '%Y-%m') AS month, SUM(visit_count) AS total
                                            FROM visits
                                            WHERE visit_date >= '$year_start' AND visit_date <= '$year_end'
                                            GROUP BY month
                                        ")->fetchAll(PDO::FETCH_ASSOC);

        // Combina los datos existentes con los meses generados
        foreach ($monthly as $row) {
          $month              = date('n', strtotime($row['month']));
          $monthName          = $monthNames[$month];
          $months[$monthName] = $row['total'];
        }

        return $months;
      case 'yearly':
        // Datos por Año (últimos 10 años)
        $last_10_years_start = date('Y-m-d', strtotime('-10 years'));
        $current_year = date('Y'); // Año actual

        // Inicializamos un array para los últimos 10 años, con valor 0 para cada uno
        $years = [];
        for ($i = 0; $i < 10; $i++) {
          $year         = $current_year - $i; // Restamos para obtener los últimos 10 años
          $years[$year] = 0; // Inicializamos todos los años con 0
        }

        // Consulta los datos existentes
        $yearly = $this->connect->query("SELECT YEAR(visit_date) AS year, SUM(visit_count) AS total
                                          FROM visits
                                          WHERE visit_date >= '$last_10_years_start'
                                          GROUP BY year
                                      ")->fetchAll(PDO::FETCH_ASSOC);

        // Combina los datos existentes con los años generados
        foreach ($yearly as $row) {
          $year         = $row['year']; // Año
          $years[$year] = $row['total']; // Asignamos el total al año correspondiente
        }

        return $years;
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

  // Obtener estadísticas diarias para todas las páginas de los últimos 7 días
  public function get_page_comparative_daily_data() {
    $last_7_days_start = date('Y-m-d', strtotime('-7 days')); // Fecha de inicio de los últimos 7 días
    $current_date      = date('Y-m-d'); // Fecha actual

    // Query para obtener visitas diarias por página en los últimos 7 días
    $query = "SELECT visit_date, page, SUM(visit_count) AS total
    FROM visits
    WHERE visit_date >= '$last_7_days_start' AND visit_date <= '$current_date'
    GROUP BY visit_date, page
    ORDER BY visit_date ASC
  ";

    $result = $this->connect->query($query)->fetchAll(PDO::FETCH_ASSOC);

    // Organizar los datos para cada página
    $data = [];
    foreach ($result as $row) {
      $data[$row['page']][] = ['date' => $row['visit_date'], 'total' => $row['total']];
    }

    // Asegurarse de que haya datos para cada uno de los 7 días, incluso si no hay visitas en alguno
    // Creación de un array con los últimos 7 días, aunque no haya visitas
    $dates = [];
    for ($i = 6; $i >= 0; $i--) {
      $dates[] = date('Y-m-d', strtotime("-$i days"));
    }

    // Completar los datos de cada página con 0 para los días que no tienen registros
    foreach ($data as $page => $pageData) {
      // Crear un array con los días y visitas 0
      $pageDataWithZeroes = [];
      foreach ($dates as $date) {
        $found = false;
        foreach ($pageData as $visit) {
          if ($visit['date'] == $date) {
            $pageDataWithZeroes[] = ['date' => $date, 'total' => $visit['total']];
            $found                = true;
            break;
          }
        }
        if (!$found) {
          $pageDataWithZeroes[] = ['date' => $date, 'total' => 0];
        }
      }
      // Reemplazar los datos originales con los datos completos
      $data[$page] = $pageDataWithZeroes;
    }

    return $data;
  }

  // Obtener visitas totales por página (todo el tiempo)
  public function get_total_visits_by_page() {
    // Consulta SQL para obtener el total de visitas por página desde el inicio
    $query = "
        SELECT page, SUM(visit_count) AS total
        FROM visits
        GROUP BY page
    ";

    // Ejecutar la consulta y obtener el resultado
    $result = $this->connect->query($query)->fetchAll(PDO::FETCH_ASSOC);

    // Preparar los datos en el formato deseado (array asociativo)
    $data = [];
    foreach ($result as $row) {
      // Usamos el nombre de la página como clave y el total de visitas como valor
      $data[$row['page']] = (int) $row['total'];  // Asegúrate de que el valor es un entero
    }

    return $data;
  }
}
