<?php

/* --------------- */
// Paginador
/* --------------- */

function getPaginatedResults($table, $searchColumns, $searchTerm, $additionalConditions, $limit, $offset, $connect) {
  $searchTerm = "%$searchTerm%";
  $query      = "SELECT * FROM $table 
            WHERE (";

  $first = true;
  foreach ($searchColumns as $column) {
    if (!$first) {
      $query .= " OR ";
    }
    $query .= "$column LIKE :searchTerm";
    $first = false;
  }

  $query .= ")";

  // Add additional conditions dynamically
  foreach ($additionalConditions as $condition) {
    $query .= " AND " . $condition['sql'];
  }

  $query .= " LIMIT :limit OFFSET :offset";

  $stmt = $connect->prepare($query);
  $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
  $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

  // Bind additional condition values
  foreach ($additionalConditions as $key => $condition) {
    if (isset($condition['value'])) {
      $stmt->bindValue($condition['param'], $condition['value'], $condition['type']);
    }
  }

  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getTotalResults($table, $searchColumns, $searchTerm, $additionalConditions, $connect) {
  $searchTerm = "%$searchTerm%";
  $query      = "SELECT COUNT(*) as total FROM $table 
            WHERE (";

  $first = true;
  foreach ($searchColumns as $column) {
    if (!$first) {
      $query .= " OR ";
    }
    $query .= "$column LIKE :searchTerm";
    $first = false;
  }

  $query .= ")";

  // Add additional conditions dynamically
  foreach ($additionalConditions as $condition) {
    $query .= " AND " . $condition['sql'];
  }

  $stmt = $connect->prepare($query);
  $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);

  // Bind additional condition values
  foreach ($additionalConditions as $key => $condition) {
    if (isset($condition['value'])) {
      $stmt->bindValue($condition['param'], $condition['value'], $condition['type']);
    }
  }

  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_OBJ);
  return $result->total;
}

function renderPagination($offset, $limit, $total_results, $page, $search, $total_pages) {
  echo '<div class="row">
          <div class="col-md-6">
            <p>Mostrando ' . ($offset + 1) . ' a ' . min($offset + $limit, $total_results) . ' de ' . $total_results . ' entradas</p>
          </div>
          <div class="col-md-6">
            <ul class="pagination justify-content-end">';

  if ($page > 1) {
    echo '<li class="page-item">
            <a class="page-link" href="?search=' . $search . '&page=' . ($page - 1) . '">Anterior</a>
          </li>';
  }

  if ($page > 3) {
    echo '<li class="page-item">
            <a class="page-link" href="?search=' . $search . '&page=1">1</a>
          </li>';
    if ($page > 4) {
      echo '<li class="page-item disabled">
              <a class="page-link">...</a>
            </li>';
    }
  }

  for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++) {
    echo '<li class="page-item">
            <a class="page-link ' . ($i == $page ? 'active' : '') . '" href="?search=' . $search . '&page=' . $i . '">' . $i . '</a>
          </li>';
  }

  if ($page < $total_pages - 2) {
    if ($page < $total_pages - 3) {
      echo '<li class="page-item disabled">
              <a class="page-link">...</a>
            </li>';
    }
    echo '<li class="page-item">
            <a class="page-link" href="?search=' . $search . '&page=' . $total_pages . '">' . $total_pages . '</a>
          </li>';
  }

  if ($page < $total_pages) {
    echo '<li class="page-item">
            <a class="page-link" href="?search=' . $search . '&page=' . ($page + 1) . '">Siguiente</a>
          </li>';
  }

  echo '</ul>
        </div>
      </div>';
}

function formatDate($date) {
  $timestamp = strtotime($date);

  $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

  $day   = date('d', $timestamp);
  $month = date('n', $timestamp) - 1;
  $year  = date('Y', $timestamp);

  return "$day " . $months[$month] . " $year";
}