<?php

// --------------------------------------------------------------------------
// SECCIÓN: PERMISOS DE ACCESO (LOGIN)
// --------------------------------------------------------------------------

/**
 * Verifica si un usuario tiene autorización para iniciar sesión en el panel administrativo.
 * Basado en el sistema de metadatos (EAV).
 *
 * @param PDO $connect Conexión a la base de datos.
 * @param int $user_id ID del usuario a verificar.
 * @return bool Verdadero si el usuario puede acceder.
 */
function can_user_access_admin($connect, $user_id) {
  // Super admin siempre tiene acceso total
  if (defined('SUPERADMIN_ID') && in_array($user_id, SUPERADMIN_ID, true)) {
    return true;
  }

  // 1. Obtener el role_id desde usermeta
  $stmtRole = $connect->prepare("
    SELECT usermeta_value 
    FROM usermeta 
    WHERE user_id = :uid AND usermeta_key = 'role_id' 
    LIMIT 1
  ");
  $stmtRole->bindParam(':uid', $user_id, PDO::PARAM_INT);
  $stmtRole->execute();
  $role_id = $stmtRole->fetchColumn();

  if (!$role_id) {
    return false;
  }

  // 2. Obtener los permisos asignados al rol desde rolemeta
  $stmtPerms = $connect->prepare("
    SELECT rolemeta_value 
    FROM rolemeta 
    WHERE role_id = :rid AND rolemeta_key = 'permissions' 
    LIMIT 1
  ");
  $stmtPerms->bindParam(':rid', $role_id, PDO::PARAM_INT);
  $stmtPerms->execute();
  $permissions_raw = $stmtPerms->fetchColumn();

  $permissions = [];
  if ($permissions_raw) {
    $permissions = json_decode($permissions_raw, true) ?: [];
  }

  // 3. Obtener permisos personalizados del usuario (usermeta)
  $stmtUserPerms = $connect->prepare("SELECT usermeta_value FROM usermeta WHERE user_id = :uid AND usermeta_key = 'permissions' LIMIT 1");
  $stmtUserPerms->bindParam(':uid', $user_id, PDO::PARAM_INT);
  $stmtUserPerms->execute();
  $user_perms_raw = $stmtUserPerms->fetchColumn();
  $personalized_permissions = json_decode($user_perms_raw ?: '[]', true) ?: [];

  // Combinar ambos: Rol + Personalizados
  $all_permissions = array_unique(array_merge($permissions, $personalized_permissions));

  // 4. Verificar si tiene el permiso de entrada básico (admin:access.admin)
  return in_array('admin:access.admin', $all_permissions, true);
}

/**
 * Verifica si el usuario actual tiene un permiso específico.
 *
 * @param string $permission Llave del permiso (ej: 'users.list').
 * @param string $context Contexto del permiso (admin, front, api).
 * @return bool True si tiene permiso.
 */
function can_user_permission($permission, $context = 'admin') {
  global $connect;

  $user_id = $_SESSION['user_id'] ?? null;

  if (!$user_id) {
    return false;
  }

  // Super admin tiene acceso total automático
  if (is_superadmin()) {
    return true;
  }

  // Cache estática de permisos por sesión para evitar múltiples consultas
  static $user_permissions = null;

  if ($user_permissions === null) {
    // 1. Obtener role_id
    $stmtRole = $connect->prepare("SELECT usermeta_value FROM usermeta WHERE user_id = :uid AND usermeta_key = 'role_id' LIMIT 1");
    $stmtRole->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtRole->execute();
    $role_id = $stmtRole->fetchColumn();

    if (!$role_id) {
      $user_permissions = [];
    } else {
      // 2. Obtener permisos del rol
      $stmtPerms = $connect->prepare("SELECT rolemeta_value FROM rolemeta WHERE role_id = :rid AND rolemeta_key = 'permissions' LIMIT 1");
      $stmtPerms->bindParam(':rid', $role_id, PDO::PARAM_INT);
      $stmtPerms->execute();
      $perms_raw = $stmtPerms->fetchColumn();
      $user_permissions = json_decode($perms_raw ?: '[]', true) ?: [];
    }

    // 3. Obtener permisos personalizados del usuario (usermeta)
    $stmtUserPerms = $connect->prepare("SELECT usermeta_value FROM usermeta WHERE user_id = :uid AND usermeta_key = 'permissions' LIMIT 1");
    $stmtUserPerms->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtUserPerms->execute();
    $user_perms_raw = $stmtUserPerms->fetchColumn();
    $personalized_permissions = json_decode($user_perms_raw ?: '[]', true) ?: [];

    // Combinar ambos: El usuario tiene permiso si está en el rol O si está en su lista personalizada
    $user_permissions = array_unique(array_merge($user_permissions, $personalized_permissions));
  }

  // Formato del permiso en la base de datos: "contexto:permiso"
  $full_permission = "{$context}:{$permission}";

  return in_array($full_permission, $user_permissions, true);
}