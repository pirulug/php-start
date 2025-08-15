<?php

class StaticUrl {
  private $base_url;
  private $plugins;
  private $assets;
  private $favicon;
  private $logo;

  public function __construct($base_url) {
    $this->base_url = rtrim($base_url, '/');
    $this->plugins  = $base_url . '/static/plugins';
    $this->assets   = $base_url . '/static/assets';
    $this->favicon  = $base_url . '/uploads/site/favicons';
    $this->logo     = $base_url . '/uploads/site';
  }

  public function logo($src) {
    return "{$this->logo}/{$src}";
  }

  public function favicon($src) {
    return "{$this->favicon}/{$src}";
  }

  public function assets($type, $file) {
    return "{$this->assets}/{$type}/{$file}";
  }

  /**
   * Genera la URL para un plugin específico.
   * ejemplo: plugin('example', 'js', 'script.js')
   * http://example.com/static/plugins/example/js/script.js
   * @param string $name Nombre de la carpeta .
   * @param string $type Tipo de recurso (js, css, img).
   * @param string $file Nombre del archivo.
   */

  public function plugin($name, $type, $file) {
    return "{$this->plugins}/{$name}/{$type}/{$file}";
  }

}