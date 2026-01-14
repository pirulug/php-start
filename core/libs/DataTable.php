<?php

class DataTableServerSide {
  private PDO $db;
  private string $select = '*';
  private string $from = '';
  private string $joins = '';
  private string $where = '1=1';
  private array $columns = [];
  private array $searchable = [];
  private array $bindParams = [];

  public function __construct(PDO $db) {
    $this->db = $db;
  }

  public function select(string $select): self {
    $this->select = $select;
    return $this;
  }

  public function from(string $from): self {
    $this->from = $from;
    return $this;
  }

  public function joins(string $joins): self {
    $this->joins = $joins;
    return $this;
  }

  public function where(string $where): self {
    $this->where = $where;
    return $this;
  }

  public function columns(array $columns): self {
    $this->columns = $columns;
    return $this;
  }

  public function searchable(array $searchable): self {
    $this->searchable = $searchable;
    return $this;
  }

  public function execute(): array {
    $draw   = (int) ($_POST['draw'] ?? 1);
    $start  = (int) ($_POST['start'] ?? 0);
    $length = (int) ($_POST['length'] ?? 10);
    $search = $_POST['search']['value'] ?? '';

    $orderIndex = (int) ($_POST['order'][0]['column'] ?? 0);
    $orderDir   = strtoupper($_POST['order'][0]['dir'] ?? 'ASC');

    $orderColumn = $this->columns[$orderIndex] ?? $this->columns[0] ?? $this->select;

    $sql = "
            SELECT {$this->select}
            FROM {$this->from}
            {$this->joins}
            WHERE {$this->where}
        ";

    // Búsqueda
    if ($search && !empty($this->searchable)) {
      $likes = [];
      foreach ($this->searchable as $i => $col) {
        $likes[] = "{$col} LIKE :s{$i}";
      }
      $sql .= " AND (" . implode(' OR ', $likes) . ")";
    }

    // Total filtrado
    $stmt = $this->db->prepare($sql);

    if ($search && !empty($this->searchable)) {
      $searchParam = "%{$search}%";
      foreach ($this->searchable as $i => $col) {
        $stmt->bindValue(":s{$i}", $searchParam, PDO::PARAM_STR);
      }
    }

    $stmt->execute();
    $recordsFiltered = $stmt->rowCount();

    // Orden + paginación
    $sql  .= " ORDER BY {$orderColumn} {$orderDir} LIMIT :start, :length";
    $stmt  = $this->db->prepare($sql);

    if ($search && !empty($this->searchable)) {
      foreach ($this->searchable as $i => $col) {
        $stmt->bindValue(":s{$i}", $searchParam, PDO::PARAM_STR);
      }
    }

    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    $stmt->execute();

    return [
      'draw'            => $draw,
      'recordsTotal'    => (int) $this->db->query("SELECT COUNT(*) FROM {$this->from}")->fetchColumn(),
      'recordsFiltered' => $recordsFiltered,
      'rows'            => $stmt->fetchAll(PDO::FETCH_OBJ)
    ];
  }
}
