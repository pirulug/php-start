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