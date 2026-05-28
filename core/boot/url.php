<?php

// -----------------------------------------------------------------------------
// SECCIÓN: GENERADORES DE URLS PARA ASSETS
// -----------------------------------------------------------------------------

/**
 * Genera la etiqueta <script> completa para un archivo JS de un módulo administrativo.
 *
 * @param string $module Nombre del módulo.
 * @param string $file   Nombre del archivo (sin extensión .script.js).
 * @return string Etiqueta <script src="..."> completa.
 */
function admin_modules_script($module, $file, $ext = "script.js") {
  $url = APP_URL . "/app/admin/modules/{$module}/scripts/{$file}.{$ext}";
  return "<script src='" . $url . "'></script>";
}

/**
 * Genera la etiqueta <script> completa para un archivo JS de un módulo del frontend.
 *
 * @param string $module Nombre del módulo.
 * @param string $file   Nombre del archivo (sin extensión .script.js).
 * @return string Etiqueta <script src="..."> completa.
 */
function front_modules_script($module, $file, $ext = "script.js") {
  $url = APP_URL . "/app/front/modules/{$module}/scripts/{$file}.{$ext}";
  return "<script src='" . $url . "'></script>";
}