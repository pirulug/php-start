<?php

/**
 * =========================================================
 * ACTION: API LIST USERS
 * =========================================================
 * 
 * Expone la lista de usuarios del sistema en formato JSON.
 */

// Preparar la consulta SQL con campos seguros
$sql = "
  SELECT 
    u.user_id, 
    u.user_login, 
    u.user_nickname, 
    u.user_display_name, 
    u.user_email, 
    u.user_status, 
    u.user_created,
    r.role_name
  FROM users u
  LEFT JOIN roles r ON u.role_id = r.role_id
  WHERE u.user_deleted IS NULL
  ORDER BY u.user_id DESC
";

// Ejecutar la consulta usando PDO
$stmt = $connect->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_OBJ);

// Retornar la respuesta exitosa
echo json_encode([
  "success" => true,
  "data"    => $users
]);
exit();