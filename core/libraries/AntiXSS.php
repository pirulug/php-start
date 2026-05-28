<?php

declare(strict_types=1);

/**
 * AntiXSS
 *
 * Clase encargada de la prevención y mitigación de ataques XSS (Cross-Site Scripting)
 * mediante la sanitización y validación de datos de entrada y salida.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class AntiXSS {
  // --------------------------------------------------------------------------
  // PATRONES DE SEGURIDAD
  // --------------------------------------------------------------------------

  private const PATTERNS = [
    '/<script\b[^>]*>.*?<\/script>/is',
    '/<iframe\b(?![^>]*src=["\']https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/embed\/)[^>]*>.*?<\/iframe>/is',
    '/<object\b[^>]*>.*?<\/object>/is',
    '/<embed\b[^>]*>.*?<\/embed>/is',
    '/<applet\b[^>]*>.*?<\/applet>/is',
    '/<meta\b[^>]*>/is',
    '/<base\b[^>]*>/is',
    '/<link\b[^>]*>/is',
    '/<style\b[^>]*>.*?<\/style>/is',
    '/(javascript|vbscript|mocha|livescript)\s*:|data\s*:(?!image\/(jpeg|png|gif|webp|svg\+xml);base64)/i',
    '/\bon[a-z]+\s*=\s*(["\']?)[^>]*?\1/i',
    '/<form\b[^>]*>.*?<\/form>/is',
    '/<\?php.*?\?>/is',
    '/<\?=.*?\?>/is',
    '/<\?.*?\?>/is',
    '/<svg\b[^>]*>.*?<\/svg>/is',
    '/<math\b[^>]*>.*?<\/math>/is'
  ];

  // --------------------------------------------------------------------------
  // SECCIÓN: LIMPIEZA DE TEXTO PLANO
  // --------------------------------------------------------------------------

  /**
   * Limpia una cadena de texto eliminando contenido potencialmente peligroso.
   *
   * @param string $input Cadena de entrada cruda.
   * @param bool $escape Si es true, aplica htmlspecialchars al final.
   * @return string Cadena sanitizada.
   */
  public function clean(string $input, bool $escape = true): string {
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $input);
    $value = trim($value);

    $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $original = '';
    while ($original !== $value) {
      $original = $value;
      foreach (self::PATTERNS as $pattern) {
        $value = preg_replace($pattern, '', $value);
      }
    }

    $value = strip_tags($value);
    $value = preg_replace('/\s+/u', ' ', $value);

    if ($escape) {
      $value = htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
        'UTF-8'
      );
    }

    return trim($value);
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: LIMPIEZA DE CONTENIDO HTML
  // --------------------------------------------------------------------------

  /**
   * Limpia contenido HTML (Rich Text) permitiendo etiquetas seguras.
   *
   * @param string $input Contenido HTML crudo.
   * @param string $allowedTags Etiquetas permitidas (estilo strip_tags).
   * @return string HTML sanitizado.
   */
  public function cleanHtml(string $input, string $allowedTags = '<b><i><u><strong><em><ul><ol><li><p><br><h1><h2><h3><h4><h5><h6><a><img><div><span><iframe><lite-youtube><pre><code>'): string {
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $input);

    $original = '';
    while ($original !== $value) {
      $original = $value;
      foreach (self::PATTERNS as $pattern) {
        $value = preg_replace($pattern, '', $value);
      }
    }

    $value = strip_tags($value, $allowedTags);

    return trim($value);
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: UTILIDADES DE ARRAY
  // --------------------------------------------------------------------------

  /**
   * Limpia un array de forma recursiva aplicando los filtros de XSS.
   *
   * @param array $data Array con datos a limpiar.
   * @param bool $escape Si es true, escapa los valores de texto.
   * @return array Array sanitizado.
   */
  public function cleanArray(array $data, bool $escape = true): array {
    foreach ($data as $key => $value) {
      if (is_string($value)) {
        $data[$key] = $this->clean($value, $escape);
      } elseif (is_array($value)) {
        $data[$key] = $this->cleanArray($value, $escape);
      }
    }
    return $data;
  }
}