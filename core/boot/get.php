<?php

// --------------------------------------------------------------------------
// SECCIÓN: GESTIÓN DE SESIÓN DE USUARIO
// --------------------------------------------------------------------------

/**
 * Obtiene la información completa de un usuario, incluyendo sus metadatos (EAV)
 * y la consolidación de permisos (Rol + Individuales).
 *
 * @param PDO $connect Conexión a la base de datos.
 * @param int $user_id ID del usuario.
 * @return object|null Objeto con los datos del usuario o null si no existe.
 */
function get_user_session($connect, $user_id) {
  // --------------------------------------------------------------------------
  // 1. Datos base del Usuario
  // --------------------------------------------------------------------------
  $stmt = $connect->prepare("
    SELECT *
    FROM users
    WHERE user_id = :user_id
          AND user_status = 1
          AND user_deleted IS NULL
    LIMIT 1
  ");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_OBJ);

  if (!$user) {
    return null;
  }

  // --------------------------------------------------------------------------
  // 2. Cargar Metadatos (Patrón EAV)
  // --------------------------------------------------------------------------
  $stmtMeta = $connect->prepare("
    SELECT usermeta_key, usermeta_value
    FROM usermeta
    WHERE user_id = :user_id
  ");
  $stmtMeta->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmtMeta->execute();

  $metas = $stmtMeta->fetchAll(PDO::FETCH_OBJ);

  foreach ($metas as $meta) {
    $key = $meta->usermeta_key;
    $val = $meta->usermeta_value;

    // Decodificar JSON si se detecta formato estructurado
    if (is_string($val) && (strpos($val, '{') === 0 || strpos($val, '[') === 0)) {
      $json = json_decode($val);
      if (json_last_error() === JSON_ERROR_NONE) {
        $val = $json;
      }
    }

    // Evitar sobreescribir columnas nativas de la tabla users
    if (!property_exists($user, $key)) {
      $user->$key = $val;
    }
  }

  // --------------------------------------------------------------------------
  // 3. Información del Rol y Permisos del Rol
  // --------------------------------------------------------------------------
  $role_permissions = [];

  if (isset($user->role_id)) {
    $stmtRole = $connect->prepare("
      SELECT r.role_name, r.role_description, rm.rolemeta_value as permissions
      FROM roles r
      LEFT JOIN rolemeta rm ON rm.role_id = r.role_id AND rm.rolemeta_key = 'permissions'
      WHERE r.role_id = :role_id
      LIMIT 1
    ");
    $stmtRole->bindParam(':role_id', $user->role_id, PDO::PARAM_INT);
    $stmtRole->execute();

    $role = $stmtRole->fetch(PDO::FETCH_OBJ);
    if ($role) {
      $user->role_name        = $role->role_name;
      $user->role_description = $role->role_description;

      if ($role->permissions) {
        $role_permissions = json_decode($role->permissions, true) ?: [];
      }
    }
  }

  // --------------------------------------------------------------------------
  // 4. Consolidación de Permisos (Rol + Individuales)
  // --------------------------------------------------------------------------
  $individual_permissions = (isset($user->permissions) && is_array($user->permissions))
    ? $user->permissions
    : [];

  // Combinar ambos conjuntos y limpiar duplicados
  $user->all_permissions = array_unique(array_merge($role_permissions, $individual_permissions));

  return $user;
}