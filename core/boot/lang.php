<?php

/**
 * Motor de Idiomas (I18N)
 * 
 * Proporciona funciones de traducción similares a WordPress (__, _e).
 * Permite cargar "Text Domains" para separar traducciones por módulos.
 */

// Almacén global de traducciones: $translations[$domain][$text]
$GLOBALS['translations'] = [];

/**
 * Carga un archivo de idioma para un dominio específico.
 * 
 * @param string $domain Identificador del dominio (ej. 'admin', 'users').
 * @param string $path Ruta completa al archivo PHP que devuelve un array.
 * @return bool
 */
function load_textdomain($domain, $path) {
  if (!is_file($path)) {
    return false;
  }

  $lang_data = require $path;
  if (!is_array($lang_data)) {
    return false;
  }

  if (!isset($GLOBALS['translations'][$domain])) {
    $GLOBALS['translations'][$domain] = [];
  }

  $GLOBALS['translations'][$domain] = array_merge($GLOBALS['translations'][$domain], $lang_data);
  return true;
}

/**
 * Traduce un texto.
 * 
 * @param string $text Texto original en inglés (o llave).
 * @param string|null $domain Dominio del texto. Si es null, detecta el módulo actual.
 * @return string Texto traducido o el original si no existe.
 */
function __($text, $domain = null) {
  $translations = $GLOBALS['translations'];

  // Si no se especifica dominio, detectamos el del módulo actual vía Router
  if ($domain === null) {
    $current_route = route();
    $ctx = $current_route['context'] ?? 'default';
    $mod = $current_route['module'] ?? '';
    
    $domain = !empty($mod) ? "{$ctx}.{$mod}" : $ctx;
  }

  // 1. Buscar en el dominio solicitado o detectado (context.module)
  if (isset($translations[$domain][$text])) {
    return $translations[$domain][$text];
  }

  // 2. Buscar en el dominio del contexto (si es distinto al detectado)
  $ctx = $current_route['context'] ?? null;
  if ($ctx && $domain !== $ctx && isset($translations[$ctx][$text])) {
    return $translations[$ctx][$text];
  }

  // 3. Buscar en el dominio global (default)
  if ($domain !== 'default' && isset($translations['default'][$text])) {
    return $translations['default'][$text];
  }

  return $text;
}

/**
 * Traduce y muestra un texto.
 * 
 * @param string $text Texto a traducir.
 * @param string|null $domain Dominio del texto.
 */
function _e($text, $domain = null) {
  echo __($text, $domain);
}

/**
 * Obtiene el idioma actual del sitio. 
 * Prioriza la sesión del usuario y luego la configuración global.
 * 
 * @return string
 */
function get_locale() {
  if (isset($_SESSION['site_lang'])) {
    return $_SESSION['site_lang'];
  }

  global $config;
  return $config->get('site_lang', 'es');
}
