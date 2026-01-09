<?php

declare(strict_types=1);

/**
 * AntiXSS
 *
 * Clase encargada de la prevención y mitigación de ataques XSS
 * (Cross-Site Scripting) mediante la sanitización y validación
 * de datos de entrada y salida.
 *
 * Proporciona métodos para filtrar contenido malicioso,
 * codificar caracteres especiales y reforzar la seguridad
 * de la aplicación frente a inyecciones de scripts.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class AntiXSS {

  private const PATTERNS = [
    '/<\s*script\b[^>]*>.*?<\s*\/\s*script\s*>/is',
    '/<\s*iframe\b[^>]*>.*?<\s*\/\s*iframe\s*>/is',
    '/<\s*object\b[^>]*>.*?<\s*\/\s*object\s*>/is',
    '/<\s*embed\b[^>]*>.*?<\s*\/\s*embed\s*>/is',
    '/<\s*applet\b[^>]*>.*?<\s*\/\s*applet\s*>/is',
    '/<\s*form\b[^>]*>.*?<\s*\/\s*form\s*>/is',
    '/javascript\s*:/i',
    '/vbscript\s*:/i',
    '/data\s*:/i',
    '/on\w+\s*=\s*"[^"]*"/i',
    '/on\w+\s*=\s*\'[^\']*\'/i',
    '/on\w+\s*=\s*[^\s>]+/i'
  ];

  public function clean(string $input, bool $escape = true): string {

    $value = trim($input);

    $value = html_entity_decode(
      $value,
      ENT_QUOTES | ENT_HTML5,
      'UTF-8'
    );

    foreach (self::PATTERNS as $pattern) {
      $value = preg_replace($pattern, '', $value);
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

    return $value;
  }

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
