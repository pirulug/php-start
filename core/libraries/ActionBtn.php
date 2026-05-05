<?php

/**
 * Clase para la generación estandarizada de botones de acción.
 * Proporciona una interfaz fluida para crear botones con permisos,
 * iconos, etiquetas y confirmaciones de SweetAlert integradas.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class ActionBtn {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE CONFIGURACIÓN
  // --------------------------------------------------------------------------

  protected string $type;
  protected string $url;
  protected ?string $permission = null;
  protected string $icon = '';
  protected string $text = '';
  protected string $classes = '';
  protected string $extraAttrs = '';

  protected string $saTitle = '¿Estás seguro?';
  protected string $saText = 'Esta acción no se puede deshacer.';

  // --------------------------------------------------------------------------
  // SECCIÓN: FACTORÍA DE BOTONES (MÉTODOS ESTÁTICOS)
  // --------------------------------------------------------------------------

  /**
   * Crea un botón de tipo enlace genérico.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function link(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-secondary fw-semibold';
    return $instance;
  }

  /**
   * Crea un botón de acción principal (Guardar/Crear).
   *
   * @param string $url URL de destino. Si está vacía, actúa como submit.
   * @return self Instancia configurada.
   */
  public static function save(string $url = ''): self {
    $instance          = new self();
    $instance->type    = empty($url) ? 'submit' : 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-primary';
    $instance->icon    = 'fa-solid fa-floppy-disk';
    $instance->text    = 'Guardar';
    return $instance;
  }

  /**
   * Crea un botón para edición de registros.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function edit(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-success';
    $instance->icon    = 'fa-solid fa-pen-to-square';
    $instance->text    = 'Editar';
    return $instance;
  }

  /**
   * Crea un botón para eliminación física con confirmación SweetAlert.
   *
   * @param string $url URL de destino tras confirmar.
   * @return self Instancia configurada.
   */
  public static function delete(string $url): self {
    $instance          = new self();
    $instance->type    = 'delete';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-danger';
    $instance->icon    = 'fa-solid fa-trash-can';
    $instance->text    = 'Eliminar';
    return $instance;
  }

  /**
   * Crea un botón para ver detalles de un recurso.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function view(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-info text-white';
    $instance->icon    = 'fa-solid fa-eye';
    $instance->text    = 'Detalles';
    return $instance;
  }

  /**
   * Crea un botón de cancelación o cierre.
   *
   * @param string $url URL de destino. Si está vacía, actúa como botón normal.
   * @return self Instancia configurada.
   */
  public static function cancel(string $url = ''): self {
    $instance          = new self();
    $instance->type    = empty($url) ? 'button' : 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-secondary';
    $instance->icon    = 'fa-solid fa-xmark';
    $instance->text    = 'Cancelar';
    return $instance;
  }

  /**
   * Crea un botón para desactivación lógica (Borrado Lógico).
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function deactivate(string $url = ''): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-warning';
    $instance->icon    = 'fa-solid fa-ban';
    $instance->text    = 'Desactivar';
    return $instance;
  }

  /**
   * Crea un botón para activación de registros.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function active(string $url = ''): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-info';
    $instance->icon    = 'fa-solid fa-check';
    $instance->text    = 'Activar';
    return $instance;
  }

  /**
   * Crea un botón para gestión de llaves API.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function apiKey(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-info';
    $instance->icon    = 'fa-solid fa-key';
    $instance->text    = 'API Keys';
    return $instance;
  }

  /**
   * Crea un botón para gestión de permisos.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function permissions(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-outline-warning';
    $instance->icon    = 'fa-solid fa-user-shield';
    $instance->text    = 'Permisos';
    return $instance;
  }

  /**
   * Crea un botón para archivar recursos.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function archive(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-warning';
    $instance->icon    = 'fa-solid fa-box-archive';
    $instance->text    = 'Archivar';
    return $instance;
  }

  /**
   * Crea un botón para exportación de datos.
   *
   * @param string $url URL de destino.
   * @return self Instancia configurada.
   */
  public static function export(string $url): self {
    $instance          = new self();
    $instance->type    = 'link';
    $instance->url     = $url;
    $instance->classes = 'btn btn-sm btn-dark';
    $instance->icon    = 'fa-solid fa-file-export';
    $instance->text    = 'Exportar';
    return $instance;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Define el permiso necesario para visualizar el botón.
   *
   * @param string|null $permission Llave del permiso.
   * @return self Instancia.
   */
  public function can(?string $permission): self {
    $this->permission = $permission;
    return $this;
  }

  /**
   * Define un icono personalizado para el botón.
   *
   * @param string $icon Clase de FontAwesome o data-feather.
   * @return self Instancia.
   */
  public function icon(string $icon): self {
    $this->icon = $icon;
    return $this;
  }

  /**
   * Define el texto de la etiqueta del botón.
   *
   * @param string $text Etiqueta visible.
   * @return self Instancia.
   */
  public function text(string $text): self {
    $this->text = $text;
    return $this;
  }

  /**
   * Define clases CSS adicionales para el botón.
   *
   * @param string $classes Listado de clases.
   * @return self Instancia.
   */
  public function classes(string $classes): self {
    $this->classes = $classes;
    return $this;
  }

  /**
   * Define atributos HTML adicionales.
   *
   * @param string $attrs Atributos crudos (ej: target="_blank").
   * @return self Instancia.
   */
  public function attrs(string $attrs): self {
    $this->extraAttrs = $attrs;
    return $this;
  }

  /**
   * Define el título de la alerta de confirmación (SweetAlert).
   *
   * @param string $title Título de la alerta.
   * @return self Instancia.
   */
  public function saTitle(string $title): self {
    $this->saTitle = $title;
    return $this;
  }

  /**
   * Define el mensaje de la alerta de confirmación (SweetAlert).
   *
   * @param string $text Cuerpo del mensaje.
   * @return self Instancia.
   */
  public function saText(string $text): self {
    $this->saText = $text;
    return $this;
  }

  /**
   * Fuerza el renderizado como un elemento button de tipo submit.
   *
   * @return self Instancia.
   */
  public function asSubmit(): self {
    $this->type = 'submit';
    return $this;
  }

  /**
   * Fuerza el renderizado como un elemento button de tipo genérico.
   *
   * @return self Instancia.
   */
  public function asButton(): self {
    $this->type = 'button';
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: RENDERIZADO Y SALIDA
  // --------------------------------------------------------------------------

  /**
   * Genera el código HTML final del botón basándose en la configuración.
   * Aplica validación de permisos automáticamente.
   *
   * @return string HTML generado o cadena vacía si no tiene permisos.
   */
  public function render(): string {
    // Validación de permisos
    if (!empty($this->permission) && !can_user_permission($this->permission)) {
      return '';
    }

    // Procesamiento de iconos
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

    // Renderizado según tipo
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
   * Método mágico para renderizado automático al convertir a string.
   *
   * @return string HTML del botón.
   */
  public function __toString(): string {
    return $this->render();
  }
}