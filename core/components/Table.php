<?php

/**
 * Componente Table - Generador de elementos atómicos para tablas.
 * Cada método produce un único elemento visual, permitiendo una composición
 * total en la vista siguiendo el principio de responsabilidad única.
 */
class Table {
  public static function avatar(string $src, string $title = ''): TableAvatar {
    return new TableAvatar($src, $title);
  }

  public static function badge(string $text): TableBadge {
    return new TableBadge($text);
  }

  public static function status($val): TableStatus {
    return new TableStatus($val);
  }

  public static function text(string $text): TableText {
    return new TableText($text);
  }

  public static function date(string $date): TableDate {
    return new TableDate($date);
  }
}

/**
 * Elemento Atómico: Avatar (Círculo con imagen o inicial).
 */
class TableAvatar {
  protected string $src;
  protected string $title;
  protected string $shape = 'rounded-circle';

  public function __construct($src, $title) {
    $this->src = $src;
    $this->title = $title;
  }

  public function circle(): self {
    $this->shape = 'rounded-circle';
    return $this;
  }

  public function rounded(): self {
    $this->shape = 'rounded';
    return $this;
  }

  public function square(): self {
    $this->shape = '';
    return $this;
  }

  public function render(): string {
    $hasImage = !empty($this->src) && (strpos($this->src, 'http') === 0 || file_exists(str_replace(APP_URL, BASE_DIR, $this->src)));
    
    if ($hasImage) {
      return "<img src=\"{$this->src}\" alt=\"Avatar\" class=\"{$this->shape}\" style=\"width: 40px; height: 40px; object-fit: cover;\">";
    }

    $initial = !empty($this->title) ? strtoupper(substr($this->title, 0, 1)) : '?';
    return "
      <div class=\"{$this->shape} bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary fw-bold\" 
           style=\"width: 40px; height: 40px;\">
        {$initial}
      </div>";
  }

  public function __toString(): string {
    return $this->render();
  }
}

/**
 * Elemento Atómico: Badge (Etiqueta).
 */
class TableBadge {
  protected string $text;
  protected string $icon = '';
  protected string $color = 'primary';

  public function __construct($text) {
    $this->text = $text;
  }

  public function icon(string $icon): self {
    $this->icon = $icon;
    return $this;
  }

  public function color(string $color): self {
    $this->color = $color;
    return $this;
  }

  public function render(): string {
    $iconHtml = $this->icon ? "<i class=\"{$this->icon} me-1\"></i>" : "";
    return "
      <span class=\"badge rounded-pill bg-{$this->color} bg-opacity-10 text-{$this->color} px-3 py-2\">
        {$iconHtml} " . clear_html($this->text) . "
      </span>";
  }

  public function __toString(): string {
    return $this->render();
  }
}

/**
 * Elemento Atómico: Status (Punto indicador).
 */
class TableStatus {
  protected $val;
  protected string $on = 'Activo';
  protected string $off = 'Inactivo';

  public function __construct($val) {
    $this->val = $val;
  }

  public function labels(string $on, string $off): self {
    $this->on = $on;
    $this->off = $off;
    return $this;
  }

  public function render(): string {
    $isActive = (bool)$this->val;
    $color = $isActive ? 'success' : 'danger';
    $text  = $isActive ? $this->on : $this->off;

    return "
      <div class=\"d-flex align-items-center gap-2\">
        <span class=\"d-inline-block rounded-circle bg-{$color}\" style=\"width: 8px; height: 8px;\"></span>
        <span class=\"text-{$color} small fw-bold text-uppercase\">" . __($text) . "</span>
      </div>";
  }

  public function __toString(): string {
    return $this->render();
  }
}

/**
 * Elemento Atómico: Texto (Formateado).
 */
class TableText {
  protected string $text;
  protected bool $bold = false;
  protected bool $muted = false;
  protected bool $small = false;

  public function __construct($text) {
    $this->text = $text;
  }

  public function bold(): self {
    $this->bold = true;
    return $this;
  }

  public function muted(): self {
    $this->muted = true;
    return $this;
  }

  public function small(): self {
    $this->small = true;
    return $this;
  }

  public function render(): string {
    $classes = [];
    if ($this->bold) $classes[] = 'fw-bold';
    if ($this->muted) $classes[] = 'text-muted';
    else $classes[] = 'text-body';
    if ($this->small) $classes[] = 'small';

    $classAttr = !empty($classes) ? ' class="' . implode(' ', $classes) . '"' : '';
    return "<span{$classAttr}>" . clear_html($this->text) . "</span>";
  }

  public function __toString(): string {
    return $this->render();
  }
}

/**
 * Elemento Atómico: Fecha/Hora.
 */
class TableDate {
  protected string $date;

  public function __construct($date) {
    $this->date = $date;
  }

  public function render(): string {
    return "
      <div class=\"d-flex flex-column\">
        <span class=\"text-body small\">" . format_date($this->date) . "</span>
        <span class=\"text-muted\" style=\"font-size: 0.75rem;\">" . format_time($this->date) . "</span>
      </div>";
  }

  public function __toString(): string {
    return $this->render();
  }
}
