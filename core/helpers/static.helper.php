<?php

// -----------------------------------------------------------------------------
// SECCIÓN: CARGA DE ASSETS ESTÁTICOS
// -----------------------------------------------------------------------------

/**
 * Carga una hoja de estilo CSS del panel de administración.
 *
 * @param string $css Nombre del archivo CSS.
 * @return string Etiqueta link formateada.
 */
function static_assets_admin_css($css) {
  return "<link rel=\"stylesheet\" href=\"" . APP_URL . "/static/assets/admin/css/{$css}\">\n";
}

/**
 * Carga un script JavaScript del panel de administración.
 *
 * @param string $js Nombre del archivo JS.
 * @return string Etiqueta script formateada.
 */
function static_assets_admin_js($js) {
  return "<script src=\"" . APP_URL . "/static/assets/admin/js/{$js}\"></script>\n";
}

/**
 * Carga una hoja de estilo CSS del sitio público (frontend).
 *
 * @param string $css Nombre del archivo CSS.
 * @return string Etiqueta link formateada.
 */
function static_assets_front_css($css) {
  return "<link rel=\"stylesheet\" href=\"" . APP_URL . "/static/assets/front/css/{$css}\">\n";
}

/**
 * Carga un script JavaScript del sitio público (frontend).
 *
 * @param string $js Nombre del archivo JS.
 * @return string Etiqueta script formateada.
 */
function static_assets_front_js($js) {
  return "<script src=\"" . APP_URL . "/static/assets/front/js/{$js}\"></script>\n";
}

/**
 * Carga un archivo CSS de una librería externa.
 *
 * @param string $dir Directorio de la librería.
 * @param string $file Nombre del archivo CSS.
 * @return string Etiqueta link formateada.
 */
function static_libs_css($dir, $file) {
  return "<link rel=\"stylesheet\" href=\"" . APP_URL . "/static/libs/{$dir}/{$file}\">\n";
}

/**
 * Carga un archivo JS de una librería externa.
 *
 * @param string $dir Directorio de la librería.
 * @param string $file Nombre del archivo JS.
 * @return string Etiqueta script formateada.
 */
function static_libs_js($dir, $file) {
  return "<script src=\"" . APP_URL . "/static/libs/{$dir}/{$file}\"></script>\n";
}
