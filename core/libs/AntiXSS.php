<?php

declare(strict_types=1);

class AntiXSS {
  /**
   * Patrones peligrosos a eliminar
   */
  private const PATTERNS = [
    // Scripts y contenedores ejecutables
    '/<\s*script\b[^>]*>.*?<\s*\/\s*script\s*>/is',
    '/<\s*iframe\b[^>]*>.*?<\s*\/\s*iframe\s*>/is',
    '/<\s*object\b[^>]*>.*?<\s*\/\s*object\s*>/is',
    '/<\s*embed\b[^>]*>.*?<\s*\/\s*embed\s*>/is',
    '/<\s*applet\b[^>]*>.*?<\s*\/\s*applet\s*>/is',
    '/<\s*form\b[^>]*>.*?<\s*\/\s*form\s*>/is',

    // Protocolos peligrosos
    '/javascript\s*:/i',
    '/vbscript\s*:/i',
    '/data\s*:/i',

    // Atributos on*
    '/on\w+\s*=\s*"[^"]*"/i',
    '/on\w+\s*=\s*\'[^\']*\'/i',
    '/on\w+\s*=\s*[^\s>]+/i',
  ];

  /**
   * Limpia entradas potencialmente peligrosas (XSS)
   */
  public function clean(string $input, bool $escape = true): string {
    // Normalizar
    $clean = trim($input);

    // Eliminar HTML peligroso
    foreach (self::PATTERNS as $pattern) {
      $clean = preg_replace($pattern, '', $clean);
    }

    // Eliminar tags restantes (defensivo)
    $clean = strip_tags($clean);

    // Escape final (para output en HTML)
    if ($escape) {
      $clean = htmlspecialchars(
        $clean,
        ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
        'UTF-8'
      );
    }

    return $clean;
  }
}
