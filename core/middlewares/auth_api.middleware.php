<?php

/**
 * =========================================================
 * MIDDLEWARE: AUTH API
 * =========================================================
 * 
 * Este middleware valida el acceso a los endpoints de la API
 * mediante el uso obligatorio de una API Key válida y activa.
 */

function auth_api_middleware(array $route) {
  // Conexión global a la base de datos
  global $connect;

  // Obtener la API Key desde los parámetros GET
  $api_key = $_GET['api_key'] ?? null;

  // Validar presencia de la llave
  if (!$api_key) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      "success" => false,
      "message" => "Acceso no autorizado: API Key faltante"
    ]);
    exit();
  }

  // Consultar validez de la llave en la base de datos
  $sql = "
    SELECT user_id 
    FROM user_api_keys 
    WHERE api_key = :key 
      AND api_key_status = 1 
    LIMIT 1
  ";

  $stmt = $connect->prepare($sql);
  $stmt->bindParam(':key', $api_key);
  $stmt->execute();
  $key_data = $stmt->fetch(PDO::FETCH_OBJ);

  // Validar si la llave es correcta y está activa
  if (!$key_data) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      "success" => false,
      "message" => "Acceso no autorizado: API Key inválida o inactiva"
    ]);
    exit();
  }

  // Actualizar la fecha de último uso de la llave
  $update_sql = "
    UPDATE user_api_keys 
    SET api_key_last_used = CURRENT_TIMESTAMP 
    WHERE api_key = :key_upd
  ";

  $stmt_upd = $connect->prepare($update_sql);
  $stmt_upd->bindParam(':key_upd', $api_key);
  $stmt_upd->execute();

  // Opcional: Establecer el ID del usuario en la sesión para trazabilidad
  $_SESSION['api_user_id'] = $key_data->user_id;
}
