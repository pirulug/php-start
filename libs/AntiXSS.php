<?php

class AntiXSS {
  // Lista de patrones y reemplazos
  private $patterns = [
    '/<script\b[^>]*>(.*?)<\/script>/is'                => '', // Remover scripts
    '/<iframe\b[^>]*>(.*?)<\/iframe>/is'                => '', // Remover iframes
    '/<object\b[^>]*>(.*?)<\/object>/is'                => '', // Remover objects
    '/<embed\b[^>]*>(.*?)<\/embed>/is'                  => '', // Remover embeds
    '/<applet\b[^>]*>(.*?)<\/applet>/is'                => '', // Remover applets
    '/<form\b[^>]*>(.*?)<\/form>/is'                    => '', // Remover forms
    '/<img\b[^>]*src=["\']?javascript:[^"\']*["\']?/is' => '', // Remover img con javascript
    '/<img\b[^>]*>/is'                                  => '', // Remover todas las imágenes (opcional, según necesidades)
  ];

  // Función principal para limpiar el contenido
  public function clean($input) {
    // Aplicar la función htmlspecialchars
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');

    // Aplicar los patrones de limpieza
    foreach ($this->patterns as $pattern => $replacement) {
      $input = preg_replace($pattern, $replacement, $input);
    }

    return $input;
  }
}