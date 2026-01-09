<?php

/**
 * Gravatar
 *
 * Clase encargada de la obtención y generación de avatares
 * a través del servicio Gravatar, basados en direcciones
 * de correo electrónico.
 *
 * Permite configurar tamaño, estilo por defecto y opciones
 * de visualización de forma flexible.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Gravatar {

  protected ?string $email = null;
  protected int $size = 150;
  protected string $default = 'mp';
  protected string $rating = 'g';
  protected array $attributes = [];

  // Asignar email
  public function email(string $email): self {
    $this->email = trim(strtolower($email));
    return $this;
  }

  // Tamaño del avatar
  public function size(int $size): self {
    $this->size = $size;
    return $this;
  }

  // Imagen por defecto
  public function default(string $default): self {
    $this->default = $default;
    return $this;
  }

  // Rating permitido
  public function rating(string $rating): self {
    $this->rating = $rating;
    return $this;
  }

  // Atributos HTML
  public function attrs(array $attributes): self {
    $this->attributes = $attributes;
    return $this;
  }

  // Validación mínima
  protected function validate(): void {
    if (!$this->email) {
      throw new RuntimeException('El email es obligatorio para generar el avatar.');
    }
  }

  // Generar URL
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

  // Renderizar <img>
  public function image(): string {
    $this->validate();

    $html = '<img src="' . $this->url() . '"';

    foreach ($this->attributes as $key => $value) {
      $html .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
    }

    $html .= ' />';

    return $html;
  }
}
