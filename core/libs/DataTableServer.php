<?php
class DataTableServer {
  private $pdo;
  private $table;
  private $columns = [];
  private $joins = [];
  private $where = '';
  private $renderCallbacks = [];

  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  public function from(string $table): self {
    $this->table = $table;
    return $this;
  }

  public function columns(array $columns): self {
    $this->columns = $columns;
    return $this;
  }

  public function join(string $joinSql): self {
    $this->joins[] = $joinSql;
    return $this;
  }

  public function where(string $where): self {
    $this->where = $where;
    return $this;
  }

  public function render(string $column, callable $callback): self {
    $this->renderCallbacks[$column] = $callback;
    return $this;
  }

  public function generate(): void {
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $draw        = intval($_GET['draw'] ?? 1);
    $start       = intval($_GET['start'] ?? 0);
    $length      = intval($_GET['length'] ?? 10);
    $searchValue = trim($_GET['search']['value'] ?? '');

    $orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
    $orderColumn      = $this->columns[$orderColumnIndex]['db'] ?? $this->columns[0]['db'];
    $orderDir         = ($_GET['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

    // --- Construcción dinámica de SQL ---
    $selectCols = implode(', ', array_map(fn($c) => $c['db'] . ' AS ' . $c['dt'], $this->columns));
    $joins      = implode(' ', $this->joins);

    $where = $this->where ? "WHERE {$this->where}" : '';

    if ($searchValue) {
      $searchParts = [];
      foreach ($this->columns as $col) {
        $searchParts[] = "{$col['db']} LIKE :search";
      }
      $searchSql = implode(' OR ', $searchParts);
      $where .= $where ? " AND ($searchSql)" : "WHERE $searchSql";
    }

    // Totales
    $totalRecords = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();

    $sqlCount = "SELECT COUNT(*) FROM {$this->table} $joins $where";
    $stmt     = $this->pdo->prepare($sqlCount);
    if ($searchValue)
      $stmt->bindValue(':search', "%$searchValue%");
    $stmt->execute();
    $totalFiltered = $stmt->fetchColumn();

    // Consulta principal
    $sql = "
      SELECT $selectCols
      FROM {$this->table}
      $joins
      $where
      ORDER BY $orderColumn $orderDir
      LIMIT :start, :length
    ";

    $stmt = $this->pdo->prepare($sql);
    if ($searchValue)
      $stmt->bindValue(':search', "%$searchValue%");
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      foreach ($this->renderCallbacks as $col => $cb) {
        if (isset($row[$col])) {
          $row[$col] = $cb($row[$col], $row);
        }
      }
      $data[] = $row;
    }

    echo json_encode([
      'draw'            => $draw,
      'recordsTotal'    => $totalRecords,
      'recordsFiltered' => $totalFiltered,
      'data'            => $data
    ]);
  }
}
