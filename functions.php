<?php

require BASE_DIR_ADMIN . "/libs/AntiXSS.php";

if (!isset($_SESSION)) {
  session_start();
}

function connect() {
  $connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
  $connection->exec("SET CHARACTER SET utf8");
  return $connection;
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

function get_user_session_information($connect) {
  $sentence = $connect->query("SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1");
  $sentence = $sentence->fetch(PDO::FETCH_OBJ);
  return ($sentence) ? $sentence : false;
}

/* ------------------ */
// Encrypt & Decript
/* ------------------ */

function encrypt($string): string {
  $key       = hash('sha256', SECRET_KEY);
  $iv        = substr(hash('sha256', SECRET_IV), 0, 16);
  $encrypted = openssl_encrypt($string, METHOD, $key, 0, $iv);
  return base64_encode($encrypted);
}

function decrypt($string) {
  $key       = hash('sha256', SECRET_KEY);
  $iv        = substr(hash('sha256', SECRET_IV), 0, 16);
  $decrypted = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
  return $decrypted;
}


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

/* --------------- */
// Mensajes
/* --------------- */

function add_message($message, $type = 'success') {
  if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [];
  }
  $_SESSION['messages'][] = ['message' => $message, 'type' => $type];
}

function display_messages() {
  if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])) {
    $messages         = $_SESSION['messages'];
    $success_messages = [];
    $danger_messages  = [];

    foreach ($messages as $msg) {
      if ($msg['type'] == 'danger') {
        $danger_messages[] = $msg['message'];
      } else {
        $success_messages[] = $msg['message'];
      }
    }

    if (!empty($danger_messages)) {
      echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'><ul class='mb-0'>";
      foreach ($danger_messages as $message) {
        echo "<li>$message</li>";
      }
      echo "</ul>
      <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
      </div>";
    }

    if (!empty($success_messages)) {
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'><ul class='mb-0'>";
      foreach ($success_messages as $message) {
        echo "<li>$message</li>";
      }
      echo "</ul>
      <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
      </div>";
    }

    unset($_SESSION['messages']);
  }
}

function has_error_messages() {
  if (isset($_SESSION['messages'])) {
    foreach ($_SESSION['messages'] as $msg) {
      if ($msg['type'] == 'danger') {
        return true;
      }
    }
  }
  return false;
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