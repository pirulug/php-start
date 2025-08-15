<?php

declare(strict_types=1);

class AntiXSS {
  /**
   * Lista de patrones y reemplazos
   * Usar array estático para evitar redefinirlos en cada instancia.
   */
  private static array $patterns = [
    '/<script\b[^>]*>(.*?)<\/script>/is'                => '', // Remover scripts
    '/<iframe\b[^>]*>(.*?)<\/iframe>/is'                => '', // Remover iframes
    '/<object\b[^>]*>(.*?)<\/object>/is'                => '', // Remover objects
    '/<embed\b[^>]*>(.*?)<\/embed>/is'                  => '', // Remover embeds
    '/<applet\b[^>]*>(.*?)<\/applet>/is'                => '', // Remover applets
    '/<form\b[^>]*>(.*?)<\/form>/is'                    => '', // Remover forms
    '/<img\b[^>]*src=["\']?javascript:[^"\']*["\']?/is' => '', // Remover img con javascript
    '/<img\b[^>]*>/is'                                  => '', // Remover todas las imágenes (opcional, según necesidades)
  ];

  /**
   * Limpia el contenido de entradas maliciosas.
   * 
   * @param string $input Contenido a limpiar.
   * @return string Contenido limpio.
   */
  public function clean(string $input): string {
    // Aplicar htmlspecialchars para evitar inyección básica
    $sanitized = htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');

    // Aplicar patrones de limpieza
    foreach (self::$patterns as $pattern => $replacement) {
      // Solo aplicar si el patrón coincide
      if (preg_match($pattern, $sanitized)) {
        $sanitized = preg_replace($pattern, $replacement, $sanitized);
      }
    }
    
    return $sanitized;
  }
}
