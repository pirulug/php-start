<?php

require BASE_DIR . "/libs/AntiXSS.php";

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

function check_access($connect) {
  $sentence = $connect->query("SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' AND user_status = 1 LIMIT 1");
  $row      = $sentence->fetch(PDO::FETCH_ASSOC);
  return $row;
}

function cleardata($data) {
  $antiXss = new AntiXSS();
  $data    = $antiXss->clean($data);
  return $data;
}

function isUserLoggedIn(): bool {
  return isset($_SESSION['signedin']) && $_SESSION['signedin'] === true;
}

function get_user_session_information($connect) {
  $sentence = $connect->query("SELECT * FROM users WHERE user_id = '" . $_SESSION['user_id'] . "' LIMIT 1");
  $sentence = $sentence->fetch(PDO::FETCH_OBJ);
  return ($sentence) ? $sentence : false;
}

/* ------------------ */
// Manejo de Block
/* ------------------ */
// Array global para almacenar bloques de contenido
$blocks = [];

// Función para iniciar un bloque
function blockStart($name) {
  global $blocks;
  ob_start();
}

// Función para finalizar un bloque
function blockEnd($name) {
  global $blocks;
  $blocks[$name] = ob_get_clean();
}

// Función para imprimir un bloque
function block($name) {
  global $blocks;
  if (isset($blocks[$name])) {
    echo $blocks[$name];
  }
}

/* --------------- */
// Paginador
/* --------------- */


function getPaginatedResults($table, $searchColumns, $searchTerm, $additionalConditions, $limit, $offset, $connect, $orderColumn = 'id', $orderDirection = 'DESC') {
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

  $query .= "  ORDER BY $orderColumn $orderDirection LIMIT :limit OFFSET :offset";

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


/* --------------- */
// Gravatar
/* --------------- */

function getGravatar($email, $s = 150, $d = 'mp', $r = 'g', $img = false, $atts = array()) {
  $url = 'https://www.gravatar.com/avatar/';
  $url .= md5(strtolower(trim($email)));
  $url .= "?s=$s&d=$d&r=$r";
  if ($img) {
    $url = '<img src="' . $url . '"';
    foreach ($atts as $key => $val)
      $url .= ' ' . $key . '="' . $val . '"';
    $url .= ' />';
  }
  return $url;
}

/**
 * Cargar imagenes
 */

function uploadSiteImage($fieldName, $savedValue, $uploadsPath) {
  if (empty($_FILES[$fieldName]['name'])) {
    return $savedValue;
  } else {
    $imageFile       = explode(".", $_FILES[$fieldName]["name"]);
    $renameFile      = '.' . end($imageFile);
    $uploadDirectory = $uploadsPath;

    if (!file_exists($uploadDirectory)) {
      mkdir($uploadDirectory, 0777, true);
    }

    move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadDirectory . $fieldName . $renameFile);
    return '/uploads/site/' . $fieldName . $renameFile;
  }
}

/**
 * Tiempo de cambio
 */

function tiempoDesdeCambio($fechaCambio) {
  // Crear un objeto DateTime con la fecha del cambio
  $cambio = new DateTime($fechaCambio);

  // Crear un objeto DateTime con la fecha y hora actual
  $actual = new DateTime();

  // Calcular la diferencia entre las dos fechas
  $diferencia = $actual->diff($cambio);

  // Obtener la diferencia en segundos
  $diferenciaSegundos = ($actual->getTimestamp() - $cambio->getTimestamp());

  // Determinar la unidad de tiempo más significativa
  if ($diferenciaSegundos < 60) {
    return 'Hace ' . $diferenciaSegundos . ' segundos';
  } elseif ($diferenciaSegundos < 3600) {
    return 'Hace ' . floor($diferenciaSegundos / 60) . ' minutos';
  } elseif ($diferenciaSegundos < 86400) {
    return 'Hace ' . floor($diferenciaSegundos / 3600) . ' horas';
  } elseif ($diferenciaSegundos < 604800) { // 7 días
    return 'Hace ' . floor($diferenciaSegundos / 86400) . ' días';
  } elseif ($diferencia->y > 0) {
    return 'Hace ' . $diferencia->y . ' años';
  } elseif ($diferencia->m > 0) {
    return 'Hace ' . $diferencia->m . ' meses';
  } elseif ($diferencia->d >= 7) {
    return 'Hace ' . floor($diferencia->d / 7) . ' semanas';
  } else {
    return 'Hace ' . $diferencia->d . ' días';
  }
}