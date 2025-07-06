<?php

class Paginator {
  protected $pdo;
  protected $table;
  protected $perPage;
  protected $searchTerm;
  protected $currentPage;
  protected $searchColumns;
  protected $additionalConditions;
  protected $orderColumn;
  protected $orderDirection;

  public $totalItems;
  public $totalPages;

  public function __construct($pdo, $table, $perPage = 10) {
    $this->pdo                  = $pdo;
    $this->table                = $table;
    $this->perPage              = $perPage;
    $this->searchTerm           = $_GET['search'] ?? '';
    $this->currentPage          = max((int) ($_GET['page'] ?? 1), 1);
    $this->searchColumns        = [];
    $this->additionalConditions = [];
    $this->orderColumn          = 'id';
    $this->orderDirection       = 'DESC';
  }

  public function setSearchColumns(array $columns) {
    $this->searchColumns = $columns;
  }

  public function setOrder($column, $direction = 'DESC') {
    $this->orderColumn    = $column;
    $this->orderDirection = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
  }

  public function setAdditionalConditions(array $conditions) {
    $this->additionalConditions = $conditions;
  }

  public function getResults(array $columns = ['*']) {
    $offset = ($this->currentPage - 1) * $this->perPage;

    // Build WHERE clause
    $whereClauses = [];
    $params       = [];

    // Search
    if ($this->searchTerm && $this->searchColumns) {
      $searchParts = [];
      foreach ($this->searchColumns as $i => $col) {
        $key           = ":search$i";
        $searchParts[] = "$col LIKE $key";
        $params[$key]  = '%' . $this->searchTerm . '%';
      }
      $whereClauses[] = '(' . implode(' OR ', $searchParts) . ')';
    }

    // Additional conditions
    foreach ($this->additionalConditions as $cond) {
      $whereClauses[] = $cond['sql'];
      if ($cond['param']) {
        $params[$cond['param']] = $cond['value'];
      }
    }

    $whereSQL = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

    // Query
    // $sql  = "SELECT * FROM {$this->table} $whereSQL ORDER BY {$this->orderColumn} {$this->orderDirection} LIMIT :offset, :limit";
    $colStr = implode(', ', $columns);
    $sql    = "SELECT $colStr FROM {$this->table} $whereSQL ORDER BY {$this->orderColumn} {$this->orderDirection} LIMIT :offset, :limit";
    $stmt   = $this->pdo->prepare($sql);

    // Bind dynamic params
    foreach ($params as $key => $val) {
      $stmt->bindValue($key, $val);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $this->perPage, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Count total
    $countSQL  = "SELECT COUNT(*) FROM {$this->table} $whereSQL";
    $countStmt = $this->pdo->prepare($countSQL);
    foreach ($params as $key => $val) {
      $countStmt->bindValue($key, $val);
    }
    $countStmt->execute();
    $this->totalItems = $countStmt->fetchColumn();
    $this->totalPages = ceil($this->totalItems / $this->perPage);

    return $results;
  }

  public function renderLinks($baseUrl = '?') {
    if ($this->totalPages <= 1)
      return '';

    $offset        = ($this->currentPage - 1) * $this->perPage;
    $limit         = $this->perPage;
    $search        = urlencode($this->searchTerm);
    $page          = $this->currentPage;
    $total_results = $this->totalItems;
    $total_pages   = $this->totalPages;

    $html = '<div class="row">
        <div class="col-md-6">
            <p>Mostrando ' . ($offset + 1) . ' a ' . min($offset + $limit, $total_results) . ' de ' . $total_results . ' entradas</p>
        </div>
        <div class="col-md-6">
            <ul class="pagination justify-content-end">';

    // Anterior
    if ($page > 1) {
      $html .= '<li class="page-item">
                    <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . ($page - 1) . '">Anterior</a>
                  </li>';
    }

    // Primera página y elipsis
    if ($page > 3) {
      $html .= '<li class="page-item">
                    <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=1">1</a>
                  </li>';
      if ($page > 4) {
        $html .= '<li class="page-item disabled">
                        <a class="page-link">...</a>
                      </li>';
      }
    }

    // Rango dinámico de páginas
    for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++) {
      $active = $i == $page ? ' active' : '';
      $html .= '<li class="page-item' . $active . '">
                    <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . $i . '">' . $i . '</a>
                  </li>';
    }

    // Elipsis y última página
    if ($page < $total_pages - 2) {
      if ($page < $total_pages - 3) {
        $html .= '<li class="page-item disabled">
                        <a class="page-link">...</a>
                      </li>';
      }
      $html .= '<li class="page-item">
                    <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . $total_pages . '">' . $total_pages . '</a>
                  </li>';
    }

    // Siguiente
    if ($page < $total_pages) {
      $html .= '<li class="page-item">
                    <a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . ($page + 1) . '">Siguiente</a>
                  </li>';
    }

    $html .= '</ul>
        </div>
    </div>';

    return $html;
  }
}
