<?php

class ActionBtn {
  protected string $type;
  protected string $url;
  protected ?string $permission = null;
  protected string $icon = '';
  protected string $text = '';
  protected string $classes = '';
  protected string $extraAttrs = '';

  // Atributos de SweetAlert (solo para botones de eliminación)
  protected string $saTitle = '¿Estás seguro?';
  protected string $saText = 'Esta acción no se puede deshacer.';

  /**
   * 0. Botón genérico (Enlace)
   */
  public static function link(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-secondary fw-semibold';
    return $instance;
  }

  /**
   * 1. Guardar, crear o enviar (Acción Principal)
   */
  public static function save(string $url = ''): self {
    $instance          = new self();
    $instance->type    = empty($url) ? 'submit' : 'link'; // Inteligente: Si no hay URL, asume que es un submit de formulario
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-primary';
    $instance->icon    = 'fas fa-save';
    $instance->text    = 'Guardar';
    return $instance;
  }

  /**
   * 2. Editar o modificar
   */
  public static function edit(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-success';
    $instance->icon    = 'fas fa-pen';
    $instance->text    = 'Editar';
    return $instance;
  }

  /**
   * 3. Eliminar o acciones destructivas (SweetAlert Integrado)
   */
  public static function delete(string $url): self {
    $instance          = new self();
    $instance->type    = 'delete';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-danger';
    $instance->icon    = 'fas fa-trash';
    $instance->text    = 'Eliminar';
    return $instance;
  }

  /**
   * 4. Ver más, detalles o información
   */
  public static function view(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-info text-white';
    $instance->icon    = 'fas fa-eye';
    $instance->text    = 'Ver detalles';
    return $instance;
  }

  /**
   * 5. Cancelar, cerrar o acciones neutras
   */
  public static function cancel(string $url = ''): self {
    $instance          = new self();
    $instance->type    = empty($url) ? 'button' : 'link'; // Inteligente: Si no hay URL, es un botón normal (ej. cerrar modal)
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-secondary';
    $instance->icon    = 'fas fa-times';
    $instance->text    = 'Cancelar';
    return $instance;
  }

  public static function deactivate(string $url = ''): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-warning';
    $instance->icon    = 'fa fa-ban';
    $instance->text    = 'Desactivar';
    return $instance;
  }

  public static function active(string $url = ''): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-info';
    $instance->icon    = 'fa fa-check';
    $instance->text    = 'Activar';
    return $instance;
  }

  /**
   * Gestionar API Keys
   */
  public static function apiKey(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-info';
    $instance->icon    = 'fas fa-key';
    $instance->text    = 'API Keys';
    return $instance;
  }

  /**
   * 6. Archivar, pausar o advertencias
   */
  public static function archive(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-warning';
    $instance->icon    = 'fas fa-archive';
    $instance->text    = 'Archivar';
    return $instance;
  }

  /**
   * 7. Descargar, exportar o acciones globales
   */
  public static function export(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-dark';
    $instance->icon    = 'fas fa-file-export';
    $instance->text    = 'Exportar';
    return $instance;
  }

  // ========================================================
  // MÉTODOS FLUENT (ENCADENABLES)
  // ========================================================

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

  /**
   * Fuerza a que el elemento sea un <button type="submit">
   */
  public function asSubmit(): self {
    $this->type = 'submit';
    return $this;
  }

  /**
   * Fuerza a que el elemento sea un <button type="button">
   */
  public function asButton(): self {
    $this->type = 'button';
    return $this;
  }

  // ========================================================
  // RENDERIZADO
  // ========================================================

  public function render(): string {
    // Validar el permiso (usando tu función global can)
    if (!empty($this->permission) && !can($this->permission)) {
      return '';
    }

    // ========================================================
    // RESOLVER ICONOS (Soporte para FontAwesome, Bootstrap y Feather)
    // ========================================================
    $iconHtml = '';
    if (!empty($this->icon)) {
      if (strpos($this->icon, 'data-feather') !== false) {
        // Formato crudo: data-feather="circle"
        $iconHtml = "<i {$this->icon}></i>";
      } elseif (strpos($this->icon, 'feather-') === 0) {
        // Atajo para Feather: feather-circle
        $featherIcon = substr($this->icon, 8); // Extrae lo que va después de "feather-"
        $iconHtml    = "<i data-feather=\"{$featherIcon}\"></i>";
      } else {
        // FontAwesome o Bootstrap Icons (por defecto usa la etiqueta class)
        $iconHtml = "<i class=\"{$this->icon}\"></i>";
      }
    }

    $textHtml = $this->text ? "<span>{$this->text}</span>" : "";
    $spacing  = ($this->icon && $this->text) ? " " : "";

    $content = $iconHtml . $spacing . $textHtml;

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

  /**
   * Este método mágico permite renderizar el botón automáticamente al hacer un `echo` o `print`
   */
  public function __toString(): string {
    return $this->render();
  }
}