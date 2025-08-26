<?php

class TemplateEngine {
  private array $blocks = [];
  private array $vars = [];       // variables por render
  private array $globals = [];    // variables globales
  private ?string $currentBlock = null;

  public function __construct() {
    // en modo sin cache, ya no necesitamos $cacheDir ni debug
  }

  /* ===========================
     GLOBALES
  ============================ */
  public function setGlobals(array $vars): void {
    $this->globals = array_merge($this->globals, $vars);
  }

  /* ===========================
     BLOQUES
  ============================ */
  public function blockStart(string $name): void {
    $this->currentBlock = $name;
    ob_start();
  }

  public function blockEnd(): void {
    if ($this->currentBlock) {
      $this->blocks[$this->currentBlock] = ob_get_clean();
      $this->currentBlock                = null;
    }
  }

  public function block(string $name): void {
    if (isset($this->blocks[$name])) {
      echo $this->blocks[$name];
    }
  }

  /* ===========================
     RENDER CON LAYOUT
  ============================ */
  public function render(string $viewFile, array $vars = [], ?string $layoutFile = null): void {
    $this->vars = array_merge($this->globals, $vars);

    // reset de bloques
    $this->blocks       = [];
    $this->currentBlock = null;

    $theme = $this;
    extract($this->vars, EXTR_SKIP);

    // Capturar salida de la vista
    ob_start();
    require $viewFile;
    $viewContent = ob_get_clean();

    // ✅ Si no definieron "content", usar toda la vista como content
    if (!isset($this->blocks["content"])) {
      $this->blocks["content"] = $viewContent;
    }

    // Si hay layout
    if ($layoutFile) {
      $theme = $this;
      extract($this->vars, EXTR_SKIP);

      ob_start();
      require $layoutFile;
      $content = ob_get_clean();

      echo $content;
    } else {
      // Si no hay layout, imprimir content directo
      echo $this->blocks["content"];
    }
  }

}
