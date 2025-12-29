<?php

class PaginatorPlus {
  protected $pdo;

  protected $table;
  protected $columns = ['*'];
  protected $joins = [];
  protected $wheres = [];
  protected $bindings = [];

  protected $searchColumns = [];
  protected $searchTerm;

  protected $groupBy = [];
  protected $orderColumn = null;
  protected $orderDirection = 'DESC';

  protected $perPage = 10;
  protected $currentPage = 1;

  protected $compiledWhere = null;

  public $totalItems = 0;
  public $totalPages = 0;

  /* --------------------------------------------------
   * CONSTRUCTOR
   * -------------------------------------------------- */
  public function __construct(PDO $pdo) {
    $this->pdo         = $pdo;
    $this->searchTerm  = $_GET['search'] ?? '';
    $this->currentPage = max((int) ($_GET['page'] ?? 1), 1);
  }

  /* --------------------------------------------------
   * FROM
   * -------------------------------------------------- */
  public function from($table) {
    $this->table = $table;
    return $this;
  }

  /* --------------------------------------------------
   * SELECT
   * -------------------------------------------------- */
  public function select(array $columns) {
    $this->columns = $columns;
    return $this;
  }

  /* --------------------------------------------------
   * JOIN
   * -------------------------------------------------- */
  public function join($table, $first, $operator, $second, $type = 'INNER') {
    $this->joins[] = strtoupper($type) . " JOIN {$table} ON {$first} {$operator} {$second}";
    return $this;
  }

  /* --------------------------------------------------
   * WHERE
   * -------------------------------------------------- */
  public function where($column, $operator, $value) {
    $key                  = ':w_' . count($this->bindings);
    $this->wheres[]       = "{$column} {$operator} {$key}";
    $this->bindings[$key] = $value;
    return $this;
  }

  /* --------------------------------------------------
   * SEARCH
   * -------------------------------------------------- */
  public function search(array $columns) {
    $this->searchColumns = $columns;

    if ($this->searchTerm && $columns) {
      $searchParts = [];

      foreach ($columns as $col) {
        $key                  = ':s_' . count($this->bindings);
        $searchParts[]        = "{$col} LIKE {$key}";
        $this->bindings[$key] = '%' . $this->searchTerm . '%';
      }

      $this->wheres[] = '(' . implode(' OR ', $searchParts) . ')';
    }

    return $this;
  }

  /* --------------------------------------------------
   * GROUP BY
   * -------------------------------------------------- */
  public function groupBy($columns) {
    $this->groupBy = (array) $columns;
    return $this;
  }

  /* --------------------------------------------------
   * ORDER BY
   * -------------------------------------------------- */
  public function orderBy($column, $direction = 'DESC') {
    $this->orderColumn    = $column;
    $this->orderDirection = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
    return $this;
  }

  /* --------------------------------------------------
   * PER PAGE
   * -------------------------------------------------- */
  public function perPage($perPage) {
    $this->perPage = (int) $perPage;
    return $this;
  }

  /* --------------------------------------------------
   * GET RESULTS
   * -------------------------------------------------- */
  public function get() {
    $offset = ($this->currentPage - 1) * $this->perPage;

    $where = $this->compileWhere();

    $sql = "SELECT " . implode(', ', $this->columns)
      . " FROM {$this->table}"
      . ($this->joins ? ' ' . implode(' ', $this->joins) : '')
      . ($where ? ' WHERE ' . $where : '')
      . ($this->groupBy ? ' GROUP BY ' . implode(', ', $this->groupBy) : '')
      . ($this->orderColumn ? " ORDER BY {$this->orderColumn} {$this->orderDirection}" : '')
      . " LIMIT :offset, :limit";

    $stmt = $this->pdo->prepare($sql);

    foreach ($this->bindings as $key => $value) {
      $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $this->perPage, PDO::PARAM_INT);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    $this->countTotal($where);

    return $results;
  }

  /* --------------------------------------------------
   * COMPILE WHERE (UNA SOLA VEZ)
   * -------------------------------------------------- */
  protected function compileWhere() {
    if ($this->compiledWhere !== null) {
      return $this->compiledWhere;
    }

    $this->compiledWhere = implode(' AND ', $this->wheres);
    return $this->compiledWhere;
  }

  /* --------------------------------------------------
   * COUNT TOTAL (REUSA WHERE)
   * -------------------------------------------------- */
  protected function countTotal($where) {
    $sql = "SELECT COUNT(*) FROM {$this->table}"
      . ($this->joins ? ' ' . implode(' ', $this->joins) : '')
      . ($where ? ' WHERE ' . $where : '');

    $stmt = $this->pdo->prepare($sql);

    foreach ($this->bindings as $key => $value) {
      $stmt->bindValue($key, $value);
    }

    $stmt->execute();

    $this->totalItems = (int) $stmt->fetchColumn();
    $this->totalPages = (int) ceil($this->totalItems / $this->perPage);
  }

  /* --------------------------------------------------
   * RENDER LINKS (COMPLETO, SIN CAMBIOS)
   * -------------------------------------------------- */
  public function renderLinks($baseUrl = '?') {
    if ($this->totalPages <= 1) {
      return '';
    }

    $offset = ($this->currentPage - 1) * $this->perPage;
    $limit  = $this->perPage;
    $search = urlencode($this->searchTerm);
    $page   = $this->currentPage;
    $total  = $this->totalPages;

    $html = '<div class="row">
            <div class="col-md-6">
                <p>Mostrando ' . ($offset + 1) . ' a ' . min($offset + $limit, $this->totalItems) . ' de ' . $this->totalItems . ' entradas</p>
            </div>
            <div class="col-md-6">
                <ul class="pagination justify-content-end">';

    if ($page > 1) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . ($page - 1) . '">Anterior</a></li>';
    }

    if ($page > 3) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=1">1</a></li>';
      if ($page > 4) {
        $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
      }
    }

    for ($i = max(1, $page - 2); $i <= min($page + 2, $total); $i++) {
      $active  = $i === $page ? ' active' : '';
      $html   .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . $i . '">' . $i . '</a></li>';
    }

    if ($page < $total - 2) {
      if ($page < $total - 3) {
        $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
      }
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . $total . '">' . $total . '</a></li>';
    }

    if ($page < $total) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'search=' . $search . '&page=' . ($page + 1) . '">Siguiente</a></li>';
    }

    $html .= '</ul></div></div>';

    return $html;
  }
}
