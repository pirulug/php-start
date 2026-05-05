<?php

/**
 * Middleware para el control de acceso basado en permisos.
 * Utiliza el sistema descentralizado (EAV) para validar capacidades del usuario.
 * 
 * @param array $route Datos de la ruta actual resuelta.
 * @param string $permission Nombre del permiso a validar.
 * @return void
 */
function permission_middleware(array $route, string $permission) {
  // 1. Verificar autenticación básica
  if (!is_logged_in()) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(401);
    echo json_encode([
      'status'  => 401,
      'success' => false,
      'message' => 'No autenticado'
    ]);
    exit();
  }

  // 2. Determinar contexto y validar permiso usando helpers globales
  $context = $route['context'] ?? 'front';
  $has_permission = false;

  if ($context === 'admin') {
    $has_permission = is_user_permission_admin($permission);
  } else {
    $has_permission = is_user_permission_front($permission);
  }

  // 3. Bloqueo si no tiene el permiso requerido
  if (!$has_permission) {
    // Si es una petición de API o Endpoint respondemos con JSON
    $requested_url = $_GET['url'] ?? '';
    $is_api = str_starts_with($requested_url, PATH_API) || str_contains($requested_url, '/endpoint/');

    if ($is_api) {
      header('Content-Type: application/json; charset=utf-8');
      http_response_code(403);
      echo json_encode([
        'status'  => 403,
        'success' => false,
        'message' => 'Acceso denegado: No tienes el permiso [' . $permission . ']'
      ]);
      exit();
    }

    // Para navegación web estándar, mostramos error 403
    http_response_code(403);
    
    // -----------------------------------------------------------------------------
    // NOTA: Se recomienda cargar una vista de error 403 personalizada aquí.
    // -----------------------------------------------------------------------------
    exit('Acceso denegado: No tienes permisos suficientes para realizar esta acción (Permiso requerido: ' . $permission . ').');
  }
}