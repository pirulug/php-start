<?php

// --------------------------------------------------------------------------
// SECCIÓN: CONSTRUCTORES DE RUTA (ROUTING)
// --------------------------------------------------------------------------

/**
 * Genera URLs absolutas para el panel de administración.
 *
 * @param string $path Ruta base (ej. "users/edit").
 * @param array $params Parámetros para URL amigable (ej. [1]).
 * @param array $get Parámetros de consulta (?key=val).
 * @return string URL construida.
 */
function admin_route($path = '', $params = [], $get = []) {
  $path = trim($path, '/');

  // 1. Construir la base con el PATH_ADMIN
  if ($path === '') {
    $url = '/' . PATH_ADMIN;
  } elseif (strpos($path, PATH_ADMIN) === 0) {
    $url = '/' . $path;
  } else {
    $url = '/' . PATH_ADMIN . '/' . $path;
  }

  // 2. Parámetros de ruta (URL Amigable: /valor1/valor2)
  if (!empty($params)) {
    if (!is_array($params)) {
      $params = [$params];
    }

    foreach ($params as $value) {
      $url .= '/' . urlencode(trim((string) $value, '/'));
    }
  }

  // 3. Parámetros GET (Query String: ?key=value)
  if (!empty($get)) {
    $url .= '?' . http_build_query($get);
  }

  return $url;
}

/**
 * Genera URLs para el sitio público de forma dinámica.
 *
 * @param string $path Ruta base (ej. "posts").
 * @param array $params Parámetros para URL amigable.
 * @param array $get Parámetros de consulta.
 * @return string URL construida.
 */
function front_route($path = '', $params = [], $get = []) {
  $path = trim($path, '/');
  $url  = '/' . $path;

  // 1. Parámetros de ruta
  if (!empty($params)) {
    if (!is_array($params)) {
      $params = [$params];
    }
    foreach ($params as $value) {
      $url .= '/' . urlencode(trim((string) $value, '/'));
    }
  }

  // 2. Parámetros GET
  if (!empty($get)) {
    $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($get);
  }

  return $url;
}

/**
 * Genera URLs para la API global.
 *
 * @param string $path Ruta base (ej. "users/list").
 * @param array $params Parámetros para URL amigable.
 * @param array $get Parámetros de consulta.
 * @return string URL construida.
 */
function api_route($path = '', $params = [], $get = []) {
  $path = trim($path, '/');

  if ($path === '') {
    $url = '/' . PATH_API;
  } elseif (strpos($path, PATH_API) === 0) {
    $url = '/' . $path;
  } else {
    $url = '/' . PATH_API . '/' . $path;
  }

  if (!empty($params)) {
    if (!is_array($params)) {
      $params = [$params];
    }
    foreach ($params as $value) {
      $url .= '/' . urlencode(trim((string) $value, '/'));
    }
  }

  if (!empty($get)) {
    $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($get);
  }

  return $url;
}

// --------------------------------------------------------------------------
// SECCIÓN: CONSTRUCTORES DE ENDPOINTS (API INTERNA)
// --------------------------------------------------------------------------

/**
 * Genera la URL para un endpoint del panel de administración.
 *
 * @param string $path   Ruta del endpoint (ej. "users/delete").
 * @param array  $params Parámetros de ruta.
 * @param array  $get    Parámetros GET.
 * @return string URL del endpoint.
 */
function admin_endpoint($path, $params = [], $get = []) {
  $parts = explode('/', trim($path, '/'));
  if (count($parts) < 2) return '';

  $module = array_shift($parts);
  $file   = implode('/', $parts);

  return admin_route("{$module}/endpoint/{$file}", $params, $get);
}

/**
 * Genera la URL para un endpoint del frontend.
 *
 * @param string $path   Ruta del endpoint (ej. "auth/check-autologin").
 * @param array  $params Parámetros de ruta.
 * @param array  $get    Parámetros GET.
 * @return string URL del endpoint.
 */
function front_endpoint($path, $params = [], $get = []) {
  $parts = explode('/', trim($path, '/'));
  if (count($parts) < 2) return '';

  $module = array_shift($parts);
  $file   = implode('/', $parts);

  return front_route("{$module}/endpoint/{$file}", $params, $get);
}
