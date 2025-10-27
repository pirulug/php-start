<?php

class PaginatorPlus {
  protected PDO $pdo;
  protected string $table = '';
  protected array $columns = ['*'];
  protected array $joins = [];
  protected array $searchColumns = [];
  protected array $conditions = [];
  protected string $orderColumn = 'id';
  protected string $orderDirection = 'DESC';
  protected int $perPage = 10;
  protected int $currentPage = 1;
  protected string $searchTerm = '';
  protected int $fetchMode = PDO::FETCH_OBJ; // Por defecto devuelve objetos

  public int $totalItems = 0;
  public int $totalPages = 0;

  public function __construct(PDO $pdo) {
    $this->pdo         = $pdo;
    $this->searchTerm  = trim($_GET['search'] ?? '');
    $this->currentPage = max((int) ($_GET['page'] ?? 1), 1);
  }

  public function from(string $table): self {
    $this->table = $table;
    return $this;
  }

  public function columns(array $columns): self {
    $this->columns = $columns;
    return $this;
  }

  public function join(string $sql): self {
    $this->joins[] = $sql;
    return $this;
  }

  public function searchColumns(array $columns): self {
    $this->searchColumns = $columns;
    return $this;
  }

  public function condition(string $sql, ?string $param = null, $value = null): self {
    $this->conditions[] = compact('sql', 'param', 'value');
    return $this;
  }

  public function order(string $column, string $direction = 'DESC'): self {
    $this->orderColumn    = $column;
    $this->orderDirection = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
    return $this;
  }

  public function perPage(int $limit): self {
    $this->perPage = $limit;
    return $this;
  }

  public function fetchMode(int $mode): self {
    $this->fetchMode = $mode;
    return $this;
  }

  public function get(): array {
    if (!$this->table) {
      throw new Exception('No se ha definido la tabla con ->from()');
    }

    $offset       = ($this->currentPage - 1) * $this->perPage;
    $params       = [];
    $whereClauses = [];

    // Búsqueda
    if ($this->searchTerm && $this->searchColumns) {
      $parts = [];
      foreach ($this->searchColumns as $i => $col) {
        $key          = ":search$i";
        $parts[]      = "$col LIKE $key";
        $params[$key] = "%{$this->searchTerm}%";
      }
      $whereClauses[] = '(' . implode(' OR ', $parts) . ')';
    }

    // Condiciones adicionales
    foreach ($this->conditions as $cond) {
      $whereClauses[] = $cond['sql'];
      if (!empty($cond['param'])) {
        $params[$cond['param']] = $cond['value'];
      }
    }

    $whereSQL = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
    $joinSQL  = $this->joins ? ' ' . implode(' ', $this->joins) : '';
    $colStr   = implode(', ', $this->columns);

    // Consulta principal
    $sql = "SELECT $colStr FROM {$this->table} $joinSQL $whereSQL
            ORDER BY {$this->orderColumn} {$this->orderDirection}
            LIMIT :offset, :limit";

    $stmt = $this->pdo->prepare($sql);

    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $this->perPage, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll($this->fetchMode);

    // Conteo total
    $countSQL  = "SELECT COUNT(*) FROM {$this->table} $joinSQL $whereSQL";
    $countStmt = $this->pdo->prepare($countSQL);

    foreach ($params as $k => $v) {
      $countStmt->bindValue($k, $v);
    }

    $countStmt->execute();
    $this->totalItems = (int) $countStmt->fetchColumn();
    $this->totalPages = (int) ceil($this->totalItems / $this->perPage);

    return $rows;
  }

  public function renderLinks(string $baseUrl = '?'): string {
    if ($this->totalPages <= 1)
      return '';

    $html = '<nav><ul class="pagination justify-content-end">';

    $search = urlencode($this->searchTerm);
    $page   = $this->currentPage;
    $last   = $this->totalPages;

    // Botón "Primero" <<
    $disabled = $page == 1 ? ' disabled' : '';
    $html .= '<li class="page-item' . $disabled . '">
              <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=1">&laquo;&laquo;</a>
            </li>';

    // Botón "Anterior" <
    $disabled = $page == 1 ? ' disabled' : '';
    $html .= '<li class="page-item' . $disabled . '">
              <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . ($page - 1) . '">&laquo;</a>
            </li>';

    // Rango de páginas visible
    $range = 2; // cantidad de páginas a mostrar alrededor de la actual
    $start = max(1, $page - $range);
    $end   = min($last, $page + $range);

    // Mostrar primera página y puntos suspensivos si es necesario
    if ($start > 1) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=1">1</a></li>';
      if ($start > 2) {
        $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
      }
    }

    // Páginas del rango
    for ($i = $start; $i <= $end; $i++) {
      $active = $i == $page ? ' active' : '';
      $html .= "<li class='page-item{$active}'><a class='page-link' href='{$baseUrl}search={$search}&page={$i}'>{$i}</a></li>";
    }

    // Mostrar puntos y última página
    if ($end < $last) {
      if ($end < $last - 1) {
        $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
      }
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . $last . '">' . $last . '</a></li>';
    }

    // Botón "Siguiente" >
    $disabled = $page == $last ? ' disabled' : '';
    $html .= '<li class="page-item' . $disabled . '">
              <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . ($page + 1) . '">&raquo;</a>
            </li>';

    // Botón "Último" >>
    $disabled = $page == $last ? ' disabled' : '';
    $html .= '<li class="page-item' . $disabled . '">
              <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . $last . '">&raquo;&raquo;</a>
            </li>';

    $html .= '</ul></nav>';

    return $html;
  }

  public function toJSON(): void {
    $data = $this->get();
    echo json_encode([
      'page'       => $this->currentPage,
      'totalPages' => $this->totalPages,
      'totalItems' => $this->totalItems,
      'data'       => $data
    ], JSON_UNESCAPED_UNICODE);
  }
}
