<?php

/**
 * Verifica si el usuario actual ha iniciado sesión.
 * 
 * @return bool
 */
function is_logged_in() {
  return user_session() !== null;
}

/**
 * Verifica si el usuario tiene una sesión activa pero el objeto de usuario falló al cargar.
 * (Útil para depuración de sesiones huérfanas)
 */
function is_session_active() {
  return isset($_SESSION["signin"]) && $_SESSION["signin"] === true;
}

/**
 * Verifica si el usuario actual es un Super Administrador (ID en security.config.php).
 * 
 * @return bool
 */
function is_superadmin() {
  $user = user_session();
  if (!$user) return false;
  return in_array((int)$user->user_id, SUPERADMIN_ID);
}

/**
 * Verifica si el usuario actual tiene acceso administrativo.
 * Los Superadministradores tienen acceso total por defecto.
 * 
 * @return bool
 */
function is_admin() {
  if (!is_logged_in()) return false;
  
  // El Superadministrador siempre es admin
  if (is_superadmin()) return true;

  // Otros usuarios dependen de la tabla de permisos
  return can_user_access_admin(connect(), user_session()->user_id);
}

/**
 * Verifica si la ruta actual coincide con la ruta proporcionada para marcarla como activa.
 * 
 * @param string $path Ruta a verificar (ej. 'account/profile').
 * @param string $class Clase CSS a devolver si está activa.
 * @return string
 */
function is_active($path, $class = 'active') {
  $current_route = route();
  if (!isset($current_route['uri'])) return '';

  $current = trim($current_route['uri'], '/');
  $target  = trim($path, '/');

  return ($current === $target) ? $class : '';
}

/**
 * Verifica si el usuario actual tiene un rol específico.
 * 
 * @param string|int $role Nombre (string) o ID (int) del rol.
 * @return bool
 */
function is_user_role($role) {
  $user = user_session();
  if (!$user) return false;

  if (is_numeric($role)) {
    return (int)($user->role_id ?? 0) === (int)$role;
  }

  return (isset($user->role_name) && strtolower($user->role_name) === strtolower($role));
}

/**
 * Verifica si el usuario actual tiene un permiso específico en el contexto de administración.
 * 
 * @param string $permission Llave del permiso (ej. 'users.new').
 * @return bool
 */
function is_user_permission_admin($permission) {
  // El Superadministrador siempre tiene acceso total
  if (is_superadmin()) return true;

  $user = user_session();
  if (!$user || !isset($user->all_permissions)) return false;

  return in_array("admin:{$permission}", $user->all_permissions, true);
}

/**
 * Verifica si el usuario actual tiene un permiso específico en el contexto del frontend.
 * 
 * @param string $permission Llave del permiso (ej. 'account.edit').
 * @return bool
 */
function is_user_permission_front($permission) {
  // El Superadministrador siempre tiene acceso total
  if (is_superadmin()) return true;

  $user = user_session();
  if (!$user || !isset($user->all_permissions)) return false;

  return in_array("front:{$permission}", $user->all_permissions, true);
}