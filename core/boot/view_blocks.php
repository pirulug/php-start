<?php

// -----------------------------------------------------------------------------
// SECCIÓN: SISTEMA DE BLOQUES DE VISTA
// -----------------------------------------------------------------------------

/**
 * Almacenamiento global para el contenido de los bloques de vista.
 * @var array
 */
$GLOBALS['__view_blocks']   = [];

/**
 * Nombre del bloque que se está capturando actualmente.
 * @var string|null
 */
$GLOBALS['__current_block'] = null;

/**
 * Inicia la captura de un nuevo bloque de contenido.
 * Utiliza el buffer de salida de PHP para capturar todo el HTML/PHP siguiente.
 * 
 * @param string $name Nombre identificador del bloque (ej. 'title', 'js', 'css').
 */
function start_block($name) {
  $GLOBALS['__current_block'] = $name;
  ob_start();
}

/**
 * Finaliza la captura del bloque actual y guarda el contenido en el almacenamiento global.
 */
function end_block() {
  $name = $GLOBALS['__current_block'];

  if ($name === null) {
    return;
  }

  $GLOBALS['__view_blocks'][$name] = ob_get_clean();
  $GLOBALS['__current_block']      = null;
}

/**
 * Recupera el contenido almacenado de un bloque específico.
 * 
 * @param string $name    Nombre del bloque a recuperar.
 * @param string $default Valor por defecto si el bloque no existe.
 * @return string El contenido capturado del bloque.
 */
function get_block($name, $default = '') {
  return $GLOBALS['__view_blocks'][$name] ?? $default;
}

/**
 * Verifica si un bloque tiene contenido capturado y no está vacío.
 * 
 * @param string $name Nombre del bloque a verificar.
 * @return bool True si el bloque existe y tiene contenido.
 */
function has_block($name) {
  return isset($GLOBALS['__view_blocks'][$name])
    && trim($GLOBALS['__view_blocks'][$name]) !== '';
}