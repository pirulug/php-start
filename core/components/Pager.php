<?php

/**
 * Librería para la gestión y renderizado de paginación y leyendas.
 */
class Pager {
  private $total = 0;
  private $limit = 10;
  private $current = 1;
  private $params = [];
  private $itemName = "registros";

  public static function make($total, $limit = 10, $current = null): self {
    $instance = new self();
    $instance->total = (int)$total;
    $instance->limit = (int)$limit;
    $instance->current = (int)($current ?? $_GET["p"] ?? 1);
    if ($instance->current < 1) $instance->current = 1;
    $instance->params = $_GET;
    unset($instance->params["url"]);
    return $instance;
  }

  public function items(string $name): self {
    $this->itemName = $name;
    return $this;
  }

  public function legend(): string {
    $start = ($this->current - 1) * $this->limit + 1;
    $end = min($this->current * $this->limit, $this->total);

    if ($this->total === 0) {
      return "<div class=\"legend\"><span class=\"fw-bold text-secondary\">No se encontraron {$this->itemName}</span></div>";
    }

    $count = ($end - $start) + 1;
    return "
      <div class=\"legend\">
        <span class=\"fw-bold\">
          Mostrando {$start} - {$end} de {$this->total} {$this->itemName}
        </span>
      </div>";
  }

  public function render(): string {
    $totalPages = ceil($this->total / $this->limit);
    if ($totalPages <= 1) return "";

    $html = "<div class=\"paginator\"><nav><ul class=\"pagination justify-content-end mb-0\">";

    if ($this->current > 1) {
      $html .= $this->pageItem("<i class=\"fa-solid fa-angles-left small\"></i>", 1, "text-uppercase small fw-bold");
      $html .= $this->pageItem("<i class=\"fa-solid fa-chevron-left small\"></i>", $this->current - 1, "", "Anterior");
    }

    $range = 1;
    for ($i = 1; $i <= $totalPages; $i++) {
      if ($i == 1 || $i == $totalPages || ($i >= $this->current - $range && $i <= $this->current + $range)) {
        $html .= $this->pageItem($i, $i, "", "", $i == $this->current);
      } elseif ($i == $this->current - $range - 1 || $i == $this->current + $range + 1) {
        $html .= "<li class=\"page-item disabled\"><span class=\"page-link\">...</span></li>";
      }
    }

    if ($this->current < $totalPages) {
      $html .= $this->pageItem("<i class=\"fa-solid fa-chevron-right small\"></i>", $this->current + 1, "", "Siguiente");
      $html .= $this->pageItem("<i class=\"fa-solid fa-angles-right small\"></i>", $totalPages, "text-uppercase small fw-bold");
    }

    $html .= "</ul></nav></div>";
    return $html;
  }

  private function pageItem($label, $page, $class = "", $aria = "", $active = false): string {
    $activeClass = $active ? "active" : "";
    $this->params["p"] = $page;
    $url = "?" . http_build_query($this->params);
    $ariaAttr = !empty($aria) ? " aria-label=\"$aria\"" : "";

    return "
      <li class=\"page-item {$activeClass}\">
        <a class=\"page-link {$class}\" href=\"{$url}\"{$ariaAttr}>{$label}</a>
      </li>";
  }
}
