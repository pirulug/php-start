<?php

/**
 * Middleware para autenticación de API.
 * Valida el acceso mediante una API Key obligatoria y activa.
 * 
 * @param array $route Datos de la ruta actual.
 * @param mixed $params Parámetros adicionales (no utilizados).
 * @return void
 */
function auth_api_middleware(array $route, $params = null) {
  // Conexión global a la base de datos
  global $connect;

  // Obtener la API Key desde los parámetros GET
  $api_key = $_GET['api_key'] ?? null;

  // -----------------------------------------------------------------------------
  // SECCIÓN: VALIDACIÓN DE PRESENCIA DE LLAVE
  // -----------------------------------------------------------------------------
  if (!$api_key) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      "success" => false,
      "message" => "Acceso no autorizado: API Key faltante"
    ]);
    exit();
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: CONSULTA DE VALIDEZ (usermeta EAV)
  // -----------------------------------------------------------------------------
  $sql = "
    SELECT user_id, usermeta_value 
    FROM usermeta 
    WHERE usermeta_key = 'api_key' 
      AND usermeta_value LIKE :key_search
    LIMIT 1
  ";

  $stmt       = $connect->prepare($sql);
  $key_search = '%' . $api_key . '%';
  $stmt->bindParam(':key_search', $key_search);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_OBJ);

  $is_valid = false;
  $user_id  = null;

  if ($row) {
    $val  = $row->usermeta_value;
    $data = json_decode($val, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
      // Formato JSON
      if (isset($data['key']) && $data['key'] === $api_key) {
        $is_valid = true;
        $user_id  = $row->user_id;
      }
    } else {
      // Formato Texto Plano (Legacy)
      if ($val === $api_key) {
        $is_valid = true;
        $user_id  = $row->user_id;
      }
    }
  }

  // Validar si la llave es correcta
  if (!$is_valid) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      "success" => false,
      "message" => "Acceso no autorizado: API Key inválida o inactiva"
    ]);
    exit();
  }

  // -----------------------------------------------------------------------------
  // SECCIÓN: ACTUALIZACIÓN DE ÚLTIMO USO
  // -----------------------------------------------------------------------------
  if ($row && strpos($row->usermeta_value, '{') === 0) {
    $data               = json_decode($row->usermeta_value, true);
    $data['updated_at'] = date('Y-m-d H:i:s');
    $new_val            = json_encode($data);

    $update_sql = "
      UPDATE usermeta 
      SET usermeta_value = :new_val 
      WHERE user_id = :uid AND usermeta_key = 'api_key'
    ";
    
    $stmt_upd = $connect->prepare($update_sql);
    $stmt_upd->bindParam(':new_val', $new_val);
    $stmt_upd->bindParam(':uid', $user_id);
    $stmt_upd->execute();
  }

  // Establecer el ID del usuario en la sesión para trazabilidad
  $_SESSION['api_user_id'] = $user_id;
}
