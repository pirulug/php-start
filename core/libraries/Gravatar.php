<?php

/**
 * Gravatar
 *
 * Clase encargada de la obtención y generación de avatares
 * a través del servicio Gravatar, basados en direcciones
 * de correo electrónico.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Gravatar {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE CONFIGURACIÓN
  // --------------------------------------------------------------------------

  protected ?string $email = null;
  protected int $size = 150;
  protected string $default = 'mp';
  protected string $rating = 'g';
  protected array $attributes = [];

  // --------------------------------------------------------------------------
  // SECCIÓN: FACTORÍA (MÉTODOS ESTÁTICOS)
  // --------------------------------------------------------------------------

  /**
   * Punto de entrada estático para inicializar la clase con un email.
   *
   * @param string $email Correo electrónico del usuario.
   * @return self Nueva instancia configurada.
   */
  public static function email(string $email): self {
    $instance = new self();
    return $instance->setEmail($email);
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Asigna el correo electrónico de forma fluida.
   *
   * @param string $email Correo electrónico.
   * @return self Instancia.
   */
  protected function setEmail(string $email): self {
    $this->email = trim(strtolower($email));
    return $this;
  }

  /**
   * Define el tamaño del avatar en píxeles.
   *
   * @param int $size Tamaño (ej: 150).
   * @return self Instancia.
   */
  public function size(int $size): self {
    $this->size = $size;
    return $this;
  }

  /**
   * Define la imagen por defecto si el correo no tiene Gravatar.
   *
   * @param string $default Estilo (mp, identicon, monsterid, etc).
   * @return self Instancia.
   */
  public function default(string $default): self {
    $this->default = $default;
    return $this;
  }

  /**
   * Define la clasificación de contenido permitida.
   *
   * @param string $rating Clasificación (g, pg, r, x).
   * @return self Instancia.
   */
  public function rating(string $rating): self {
    $this->rating = $rating;
    return $this;
  }

  /**
   * Define atributos HTML adicionales para la etiqueta <img>.
   *
   * @param array $attributes Atributos asociativos [key => value].
   * @return self Instancia.
   */
  public function attrs(array $attributes): self {
    $this->attributes = $attributes;
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: RENDERIZADO Y SALIDA
  // --------------------------------------------------------------------------

  /**
   * Valida que los datos mínimos estén presentes.
   *
   * @throws RuntimeException Si el email no ha sido definido.
   */
  protected function validate(): void {
    if (!$this->email) {
      throw new RuntimeException('El email es obligatorio para generar el avatar.');
    }
  }

  /**
   * Genera la URL directa del avatar en los servidores de Gravatar.
   *
   * @return string URL completa.
   */
  public function url(): string {
    $this->validate();

    return sprintf(
      'https://www.gravatar.com/avatar/%s?s=%d&d=%s&r=%s',
      md5($this->email),
      $this->size,
      $this->default,
      $this->rating
    );
  }

  /**
   * Genera la etiqueta HTML <img> completa con los atributos configurados.
   *
   * @return string Código HTML generado.
   */
  public function image(): string {
    $this->validate();

    $html = '<img src="' . $this->url() . '"';

    foreach ($this->attributes as $key => $value) {
      $html .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
    }

    $html .= ' />';

    return $html;
  }

  /**
   * Método mágico para renderizado automático.
   *
   * @return string Código HTML del avatar.
   */
  public function __toString(): string {
    return $this->image();
  }
}