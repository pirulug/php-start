<?php

/**
 * Genera una URL absoluta hacia el panel de administración
 *
 * @param string $path  Ruta relativa dentro del panel (ej: "users", "posts/edit/1")
 * @param array  $params  Parámetros GET opcionales (ej: ["page" => 2])
 * @return string
 */
function panel_url(string $path = '', array $params = []): string {
  // Asegurar que no haya / duplicados
  $base = rtrim(SITE_URL_ADMIN, '/');
  $path = ltrim($path, '/');

  $url = $base . '/' . $path;

  if (!empty($params)) {
    $url .= '?' . http_build_query($params);
  }

  return $url;
}