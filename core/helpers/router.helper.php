<?php

/**
 * Genera URLs para el panel de administración de forma dinámica.
 * * @param string $path   Ruta base (ej. "branches", "users/edit").
 * @param array  $params Parámetros para URL amigable (ej. [1, "slug"]). Resultado: /admin/path/1/slug
 * @param array  $get    Parámetros de consulta (Query String). Resultado: ?key=val
 * * @return string URL construida.
 * * Ejemplos:
 * admin_route("branches/edit", [$id]);             // /admin/branches/edit/1
 * admin_route("branches", [], ['status' => 1]);    // /admin/branches?status=1
 * admin_route("branches/view", [1], ['p' => 2]);   // /admin/branches/view/1?p=2
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
    // CORRECCIÓN: Si el parámetro no es un array (ej. pasaste un ID directamente), lo convertimos a array.
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
 * Genera URLs para el sitio principal de forma dinámica.
 * @param string $path   Ruta base (ej. "posts", "category/show").
 * @param array  $params Parámetros para URL amigable (ej. [1, "slug"]). Resultado: /path/1/slug
 * @param array  $get    Parámetros de consulta (Query String). Resultado: ?key=val
 * @return string URL construida.
 */
function home_route($path = '', $params = [], $get = []) {
  $path = trim($path, '/');
  $url = '/' . $path;

  // 1. Parámetros de ruta (URL Amigable: /valor1/valor2)
  if (!empty($params)) {
    if (!is_array($params)) {
      $params = [$params];
    }
    foreach ($params as $value) {
      $url .= '/' . urlencode(trim((string) $value, '/'));
    }
  }

  // 2. Parámetros GET (Query String: ?key=value)
  if (!empty($get)) {
    $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($get);
  }

  return $url;
}

/**
 * Genera URLs para la API de forma dinámica.
 * @param string $path   Ruta base (ej. "users/list").
 * @param array  $params Parámetros para URL amigable.
 * @param array  $get    Parámetros de consulta (Query String).
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

/**
 * Genera URLs para AJAX de forma dinámica.
 * @param string $path   Ruta base (ej. "analytics/visitors").
 * @param array  $params Parámetros para URL amigable.
 * @param array  $get    Parámetros de consulta (Query String).
 * @return string URL construida.
 */
function ajax_route($path = '', $params = [], $get = []) {
  $path = trim($path, '/');

  if ($path === '') {
    $url = '/' . PATH_AJAX;
  } elseif (strpos($path, PATH_AJAX) === 0) {
    $url = '/' . $path;
  } else {
    $url = '/' . PATH_AJAX . '/' . $path;
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
