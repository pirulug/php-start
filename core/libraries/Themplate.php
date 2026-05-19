<?php

/**
 * Clase para la gestión de bloques de contenido y maquetación de layouts.
 * Sostiene la pila de bloques para permitir captura anidada y varios modos
 * de inserción.
 */
class Themplate {
  protected static $blocks = [];
  protected static $stack = [];
  protected static $current = null;

  /**
   * Inicia la captura de un bloque de contenido.
   *
   * @param string $name Nombre del bloque.
   * @param string $mode Modo de captura ("replace", "append", "prepend").
   */
  public static function start($name, $mode = "replace") {
    self::$stack[] = [
      "name" => $name,
      "mode" => $mode
    ];
    self::$current = $name;
    ob_start();
  }

  /**
   * Inicia la captura de un bloque de contenido en modo append.
   *
   * @param string $name Nombre del bloque.
   */
  public static function append($name) {
    self::start($name, "append");
  }

  /**
   * Inicia la captura de un bloque de contenido en modo prepend.
   *
   * @param string $name Nombre del bloque.
   */
  public static function prepend($name) {
    self::start($name, "prepend");
  }

  /**
   * Finaliza la captura del bloque actual y procesa el contenido.
   */
  public static function end() {
    if (empty(self::$stack)) {
      return;
    }

    $block   = array_pop(self::$stack);
    $name    = $block["name"];
    $mode    = $block["mode"];
    $content = ob_get_clean();

    if (!isset(self::$blocks[$name])) {
      self::$blocks[$name] = "";
    }

    if ($mode === "append") {
      self::$blocks[$name] .= $content;
    } elseif ($mode === "prepend") {
      self::$blocks[$name] = $content . self::$blocks[$name];
    } else {
      self::$blocks[$name] = $content;
    }

    if (!empty(self::$stack)) {
      $top           = end(self::$stack);
      self::$current = $top["name"];
    } else {
      self::$current = null;
    }
  }

  /**
   * Define el contenido de un bloque directamente de forma programática.
   *
   * @param string $name Nombre del bloque.
   * @param string $content Contenido a almacenar.
   * @param string $mode Modo de almacenamiento ("replace", "append", "prepend").
   */
  public static function define($name, $content, $mode = "replace") {
    if (!isset(self::$blocks[$name])) {
      self::$blocks[$name] = "";
    }

    if ($mode === "append") {
      self::$blocks[$name] .= $content;
    } elseif ($mode === "prepend") {
      self::$blocks[$name] = $content . self::$blocks[$name];
    } else {
      self::$blocks[$name] = $content;
    }
  }

  /**
   * Limpia por completo el contenido de un bloque.
   *
   * @param string $name Nombre del bloque.
   */
  public static function clear($name) {
    unset(self::$blocks[$name]);
  }

  /**
   * Recupera el contenido almacenado de un bloque.
   *
   * @param string $name Nombre del bloque.
   * @param string $default Valor por defecto si no existe.
   * @return string Contenido del bloque.
   */
  public static function get($name, $default = "") {
    return self::$blocks[$name] ?? $default;
  }

  /**
   * Renderiza e imprime directamente el contenido de un bloque.
   *
   * @param string $name Nombre del bloque.
   * @param string $default Valor por defecto a imprimir.
   */
  public static function render($name, $default = "") {
    echo self::get($name, $default);
  }

  /**
   * Verifica si un bloque tiene contenido.
   *
   * @param string $name Nombre del bloque.
   * @return bool True si existe y no está vacío.
   */
  public static function has($name) {
    return isset(self::$blocks[$name]) && trim(self::$blocks[$name]) !== "";
  }
}
