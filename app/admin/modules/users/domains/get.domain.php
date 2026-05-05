<?php

/**
 * Domain: Obtener Roles del sistema.
 */
function get_system_roles() {
  static $roles = null;
  if ($roles !== null)
    return $roles;

  global $connect;
  $stmt  = $connect->query("SELECT role_id, role_name FROM roles ORDER BY role_id ASC");
  $roles = $stmt->fetchAll(PDO::FETCH_OBJ);

  return $roles;
}

/**
 * Domain: Obtener datos de un usuario por ID.
 */
function get_user_by_id($id) {
  static $users = [];
  if (isset($users[$id]))
    return $users[$id];

  global $connect;
  $stmt = $connect->prepare("SELECT * FROM users WHERE user_id = :id LIMIT 1");
  $stmt->execute([':id' => $id]);
  $users[$id] = $stmt->fetch(PDO::FETCH_OBJ);

  return $users[$id];
}