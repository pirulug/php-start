<?php

/**
 * Librería para generar barras de filtrado y búsqueda de forma estandarizada.
 */
class Filter {
  private $filters = [];
  private $search = null;
  private $cleanRoute = "";
  private $method = "GET";

  public static function make(string $cleanRoute = ""): self {
    $instance = new self();
    $instance->cleanRoute = $cleanRoute;
    return $instance;
  }

  public function select(string $name, string $placeholder, array $options, ?string $valKey = null, ?string $textKey = null): self {
    $this->filters[] = [
      'type'        => 'select',
      'name'        => $name,
      'placeholder' => $placeholder,
      'options'     => $options,
      'valKey'      => $valKey,
      'textKey'     => $textKey
    ];
    return $this;
  }

  public function search(string $placeholder = "Buscar...", string $name = "search"): self {
    $this->search = [
      'name'        => $name,
      'placeholder' => $placeholder
    ];
    return $this;
  }

  public function render(): string {
    $html = "<form method=\"{$this->method}\" autocomplete=\"off\">";
    $html .= "<div class=\"d-flex flex-wrap justify-content-end align-items-center gap-2\">";

    foreach ($this->filters as $f) {
      $html .= "<select name=\"{$f['name']}\" class=\"form-select w-auto\" aria-label=\"{$f['placeholder']}\">";
      $html .= "<option value=\"\">{$f['placeholder']}</option>";

      foreach ($f['options'] as $key => $opt) {
        $value = $f['valKey'] ? $opt->{$f['valKey']} : $key;
        $text  = $f['textKey'] ? $opt->{$f['textKey']} : $opt;
        $selected = (isset($_GET[$f['name']]) && (string)$_GET[$f['name']] === (string)$value) ? 'selected' : '';
        $html .= "<option value=\"{$value}\" {$selected}>" . clear_html($text) . "</option>";
      }
      $html .= "</select>";
    }

    if ($this->search) {
      $val = clear_html($_GET[$this->search['name']] ?? '');
      $html .= "
        <div class=\"input-group w-auto flex-grow-1\" style=\"max-width: 450px;\">
          <input type=\"text\" name=\"{$this->search['name']}\" class=\"form-control\" placeholder=\"{$this->search['placeholder']}\" value=\"{$val}\">
          <button type=\"submit\" class=\"btn btn-primary px-3 text-uppercase small fw-bold text-nowrap\">
            <i class=\"fa-solid fa-magnifying-glass me-2\"></i> " . __('Filtrar') . "
          </button>
        </div>";
    }

    if ($this->hasActiveFilters()) {
      $html .= "
        <a href=\"{$this->cleanRoute}\" class=\"btn btn-outline-secondary px-3\" title=\"" . __('Limpiar filtros') . "\">
          <i class=\"fa-solid fa-filter-circle-xmark\"></i>
        </a>";
    }

    $html .= "</div></form>";
    return $html;
  }

  private function hasActiveFilters(): bool {
    foreach ($this->filters as $f) {
      if (isset($_GET[$f['name']]) && $_GET[$f['name']] !== '') return true;
    }
    if ($this->search && !empty($_GET[$this->search['name']])) return true;
    return false;
  }
}
