<?php

/**
 * Verifica si el usuario actual tiene un permiso específico.
 * Detecta automáticamente el contexto (admin/front) basado en la ruta actual.
 * 
 * @param string $permission Llave del permiso (ej. 'users.new').
 * @return bool
 */
function has_user_permission($permission) {
  $current_route = route();
  $context = $current_route['context'] ?? CTX_FRONT;

  if ($context === CTX_ADMIN) {
    return is_user_permission_admin($permission);
  }

  return is_user_permission_front($permission);
}

/**
 * Verifica si el usuario actual tiene un rol específico.
 * Alias de is_user_role() para consistencia semántica.
 * 
 * @param string|int $role Nombre o ID del rol.
 * @return bool
 */
function has_user_role($role) {
  return is_user_role($role);
}

/**
 * Verifica si el usuario actual tiene una sesión activa.
 * Alias de is_logged_in() para consistencia semántica.
 * 
 * @return bool
 */
function has_session() {
  return is_logged_in();
}

/**
 * Verifica si el usuario actual tiene un avatar configurado.
 * 
 * @return bool
 */
function has_user_avatar() {
  $user = user_session();
  return ($user && !empty($user->user_avatar));
}

/**
 * Verifica si el usuario actual tiene una API Key generada.
 * 
 * @return bool
 */
function has_api_key() {
  $user = user_session();
  return ($user && !empty($user->user_api_key));
}

/**
 * Verifica si el módulo de API está habilitado en el proyecto.
 * 
 * @return bool
 */
function has_api() {
  return defined('ENABLE_API') && ENABLE_API === true;
}

/**
 * Verifica si el módulo de Frontend está habilitado en el proyecto.
 * 
 * @return bool
 */
function has_front() {
  return defined('ENABLE_FRONT') && ENABLE_FRONT === true;
}
