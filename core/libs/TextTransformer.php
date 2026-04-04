<?php

/**
 * TextTransformer
 *
 * Clase utilitaria para la normalización, transformación y
 * ofuscación de cadenas de texto mediante una interfaz fluida.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class TextTransformer {

  protected string $text = '';
  protected array $rules = [];
  protected array $customPresets = [];

  /**
   * Constructor inicial opcional con presets personalizados
   */
  public function __construct(array $customPresets = []) {
    $this->customPresets = $customPresets;
  }

  /**
   * Establece el texto inicial a transformar
   */
  public function text(string $text): self {
    $this->text = $text;
    return $this;
  }

  /**
   * Añade una regla de reemplazo personalizada (Regex)
   */
  public function replace(string $pattern, string $replacement): self {
    $this->rules[] = function ($text) use ($pattern, $replacement) {
      return preg_replace($pattern, $replacement, $text);
    };
    return $this;
  }

  /**
   * Convierte el texto a minúsculas (soporte multibyte)
   */
  public function lowercase(): self {
    $this->rules[] = function ($text) {
      return mb_strtolower($text, 'UTF-8');
    };
    return $this;
  }

  /**
   * Convierte el texto a mayúsculas (soporte multibyte)
   */
  public function uppercase(): self {
    $this->rules[] = function ($text) {
      return mb_strtoupper($text, 'UTF-8');
    };
    return $this;
  }

  /**
   * Translitera caracteres especiales (acentos, ñ, etc.) a sus equivalentes ASCII.
   * Requiere la extensión PHP 'intl'.
   */
  public function transliterate(): self {
    $this->rules[] = function ($text) {
      if (function_exists('transliterator_transliterate')) {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', $text);
      } else {
        // Fallback artesanal si intl no está disponible
        $unwanted = [
          'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n',
          'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ñ' => 'N'
        ];
        $text     = strtr($text, $unwanted);
      }
      return $text;
    };
    return $this;
  }

  /**
   * PRESET: Generación de Slugs (URL-friendly)
   */
  public function slug(): self {
    $this->transliterate();
    $this->replace('/[^a-zA-Z0-9\-_]+/', '-');
    $this->replace('/-+/', '-');
    return $this;
  }

  /**
   * PRESET: Generación de Username seguro
   */
  public function username(): self {
    $this->transliterate();
    $this->replace('/[^a-zA-Z0-9_\.]/', '');
    return $this;
  }

  /**
   * PRESET: Transformación Leet Speak básica
   */
  public function leet(): self {
    $this->rules[] = function ($text) {
      $map = ['a' => '4', 'e' => '3', 'i' => '1', 'o' => '0', 's' => '5', 't' => '7'];
      return strtr(mb_strtolower($text, 'UTF-8'), $map);
    };
    return $this;
  }

  /**
   * PRESET: Limpieza de caracteres no seguros para identificadores
   */
  public function safe(): self {
    $this->replace('/[^\w\s\.-]/', '');
    return $this;
  }

  /**
   * Generador de IDs cortos aleatorios
   */
  public function shortId(int $length = 6, ?string $alphabet = null): self {
    $alphabet ??= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $this->rules[] = function () use ($length, $alphabet) {
      $id   = '';
      $base = strlen($alphabet);
      for ($i = 0; $i < $length; $i++) {
        $id .= $alphabet[random_int(0, $base - 1)];
      }
      return $id;
    };
    return $this;
  }

  /**
   * Resetea todas las reglas acumuladas
   */
  public function reset(): self {
    $this->rules = [];
    return $this;
  }

  /**
   * Ejecuta todas las transformaciones en orden y devuelve el resultado
   */
  public function apply(): string {
    $result = $this->text;

    foreach ($this->rules as $rule) {
      $result = $rule($result);
    }

    // Limpieza final de delimitadores en los extremos
    return trim($result, '-_ ');
  }

  /**
   * Soporte para presets personalizados o llamados dinámicos
   */
  public function __call(string $name, array $arguments): self {
    if (isset($this->customPresets[$name])) {
      foreach ($this->customPresets[$name] as $pattern => $replacement) {
        $this->replace($pattern, $replacement);
      }
      return $this;
    }

    throw new BadMethodCallException("Método $name no encontrado en TextTransformer.");
  }
}
