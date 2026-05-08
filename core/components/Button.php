<?php

/**
 * Clase para la generación estandarizada de botones de acción.
 * Proporciona una interfaz fluida para crear botones con permisos,
 * iconos, etiquetas y confirmaciones de SweetAlert integradas.
 */
class Button {
  protected string $type = 'link';
  protected string $url = '';
  protected ?string $permission = null;
  protected string $icon = '';
  protected string $text = '';
  protected string $classes = '';
  protected string $extraAttrs = '';
  protected string $saTitle = '¿Estás seguro?';
  protected string $saText = 'Esta acción no se puede deshacer.';

  public static function link(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-secondary';
    return $instance;
  }

  public static function new(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-primary text-uppercase fw-bold';
    $instance->icon    = 'fa-solid fa-plus';
    $instance->text    = 'Nuevo';
    return $instance;
  }

  public static function save(string $url = ''): self {
    $instance          = new self();
    $instance->type    = empty($url) ? 'submit' : 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-primary px-5 text-uppercase fw-bold';
    $instance->icon    = 'fa-solid fa-floppy-disk';
    $instance->text    = 'Guardar';
    return $instance;
  }

  public static function edit(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-success';
    $instance->icon    = 'fa-solid fa-pen-to-square';
    $instance->text    = 'Editar';
    return $instance;
  }

  public static function delete(string $url): self {
    $instance          = new self();
    $instance->type    = 'delete';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-danger';
    $instance->icon    = 'fa-solid fa-trash-can';
    $instance->text    = 'Eliminar';
    return $instance;
  }

  public static function view(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-info';
    $instance->icon    = 'fa-solid fa-eye';
    $instance->text    = 'Detalles';
    return $instance;
  }

  public static function cancel(string $url = ''): self {
    $instance          = new self();
    $instance->type    = empty($url) ? 'button' : 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-secondary px-5 text-uppercase fw-bold';
    $instance->icon    = 'fa-solid fa-xmark';
    $instance->text    = 'Cancelar';
    return $instance;
  }

  public static function active(string $url = ''): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-info';
    $instance->icon    = 'fa-solid fa-check';
    $instance->text    = 'Activar';
    return $instance;
  }

  public static function deactivate(string $url = ''): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-warning';
    $instance->icon    = 'fa-solid fa-ban';
    $instance->text    = 'Desactivar';
    return $instance;
  }

  public static function archive(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-warning';
    $instance->icon    = 'fa-solid fa-box-archive';
    $instance->text    = 'Archivar';
    return $instance;
  }

  public static function export(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-dark';
    $instance->icon    = 'fa-solid fa-file-export';
    $instance->text    = 'Exportar';
    return $instance;
  }

  public function can(?string $permission): self {
    $this->permission = $permission;
    return $this;
  }

  public function icon(string $icon): self {
    $this->icon = $icon;
    return $this;
  }

  public function text(string $text): self {
    $this->text = $text;
    return $this;
  }

  public function classes(string $classes): self {
    $this->classes = $classes;
    return $this;
  }

  public function attrs(string $attrs): self {
    $this->extraAttrs = $attrs;
    return $this;
  }

  public function saTitle(string $title): self {
    $this->saTitle = $title;
    return $this;
  }

  public function saText(string $text): self {
    $this->saText = $text;
    return $this;
  }

  public function asSubmit(): self {
    $this->type = 'submit';
    return $this;
  }

  public function asButton(): self {
    $this->type = 'button';
    return $this;
  }

  public function render(): string {
    if (!empty($this->permission) && !can_user_permission($this->permission)) {
      return '';
    }

    $iconHtml = '';
    if (!empty($this->icon)) {
      if (strpos($this->icon, 'data-feather') !== false) {
        $iconHtml = "<i {$this->icon}></i>";
      } elseif (strpos($this->icon, 'feather-') === 0) {
        $featherIcon = substr($this->icon, 8);
        $iconHtml    = "<i data-feather=\"{$featherIcon}\"></i>";
      } else {
        $iconHtml = "<i class=\"{$this->icon}\"></i>";
      }
    }

    $textHtml = $this->text ? "<span>{$this->text}</span>" : "";
    $spacing  = ($this->icon && $this->text) ? " " : "";
    $content  = $iconHtml . $spacing . $textHtml;

    if ($this->type === 'link') {
      return "<a href=\"{$this->url}\" class=\"{$this->classes}\" {$this->extraAttrs}>{$content}</a>";
    }

    if ($this->type === 'submit') {
      return "<button type=\"submit\" class=\"{$this->classes}\" {$this->extraAttrs}>{$content}</button>";
    }

    if ($this->type === 'button') {
      return "<button type=\"button\" class=\"{$this->classes}\" {$this->extraAttrs}>{$content}</button>";
    }

    if ($this->type === 'delete') {
      $saTitleClean = htmlspecialchars($this->saTitle, ENT_QUOTES, 'UTF-8');
      $saTextClean  = htmlspecialchars($this->saText, ENT_QUOTES, 'UTF-8');

      return "<button type=\"button\" class=\"{$this->classes}\" 
              sa-title=\"{$saTitleClean}\" 
              sa-text=\"{$saTextClean}\" 
              sa-icon=\"warning\" 
              sa-confirm-btn-text=\"Sí, eliminar\" 
              sa-cancel-btn-text=\"Cancelar\" 
              sa-redirect-url=\"{$this->url}\" {$this->extraAttrs}>
            {$content}
          </button>";
    }

    return '';
  }

  public function __toString(): string {
    return $this->render();
  }
}
