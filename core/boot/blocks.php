<?php

// -----------------------------------------------------------------------------
// SECCIÓN: SISTEMA DE BLOQUES DE VISTA (BLOCKS)
// -----------------------------------------------------------------------------

/**
 * Inicia la captura de un nuevo bloque de contenido.
 *
 * @param string $name Nombre del bloque.
 * @param string $mode Modo de captura ("replace", "append", "prepend").
 */
function block_start($name, $mode = "replace") {
  Themplate::start($name, $mode);
}

/**
 * Inicia la captura de un bloque de contenido en modo append.
 *
 * @param string $name Nombre del bloque.
 */
function block_append($name) {
  Themplate::append($name);
}

/**
 * Inicia la captura de un bloque de contenido en modo prepend.
 *
 * @param string $name Nombre del bloque.
 */
function block_prepend($name) {
  Themplate::prepend($name);
}

/**
 * Finaliza la captura del bloque actual.
 */
function block_end() {
  Themplate::end();
}

/**
 * Define el contenido de un bloque directamente de forma programática.
 *
 * @param string $name Nombre del bloque.
 * @param string $content Contenido del bloque.
 * @param string $mode Modo de almacenamiento ("replace", "append", "prepend").
 */
function block_define($name, $content, $mode = "replace") {
  Themplate::define($name, $content, $mode);
}

/**
 * Limpia el contenido de un bloque.
 *
 * @param string $name Nombre del bloque.
 */
function block_clear($name) {
  Themplate::clear($name);
}

/**
 * Recupera el contenido de un bloque.
 *
 * @param string $name Nombre del bloque.
 * @param string $default Valor por defecto.
 * @return string Contenido del bloque.
 */
function block_get($name, $default = "") {
  return Themplate::get($name, $default);
}

/**
 * Imprime el contenido de un bloque.
 *
 * @param string $name Nombre del bloque.
 * @param string $default Valor por defecto.
 */
function block_render($name, $default = "") {
  Themplate::render($name, $default);
}

/**
 * Verifica si el bloque contiene texto.
 *
 * @param string $name Nombre del bloque.
 * @return bool True si contiene texto.
 */
function block_has($name) {
  return Themplate::has($name);
}

// -----------------------------------------------------------------------------
// SECCIÓN: ALIASES DE COMPATIBILIDAD RETROACTIVA
// -----------------------------------------------------------------------------

function start_block($name, $mode = "replace") {
  block_start($name, $mode);
}

function append_block($name) {
  block_append($name);
}

function prepend_block($name) {
  block_prepend($name);
}

function end_block() {
  block_end();
}

function define_block($name, $content, $mode = "replace") {
  block_define($name, $content, $mode);
}

function clear_block($name) {
  block_clear($name);
}

function get_block($name, $default = "") {
  return block_get($name, $default);
}

function render_block($name, $default = "") {
  block_render($name, $default);
}

function has_block($name) {
  return block_has($name);
}
