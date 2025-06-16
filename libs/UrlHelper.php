<?php

class UrlHelper {
  private $domain;

  public function __construct($base_url) {
    $this->domain = $base_url;
  }

  // Método genérico para activos (imágenes, CSS, JS, etc.)
  public function asset($type, $file) {
    $validTypes = ['js', 'css', 'img', 'images'];
    if (!in_array($type, $validTypes)) {
      throw new Exception("Tipo de activo no válido: $type");
    }
    return "{$this->domain}/static/{$type}/{$file}";
  }

  // Atajo para CSS
  public function css($src) {
    return $this->asset('css', $src);
  }

  // Atajo para JS
  public function js($src) {
    return $this->asset('js', $src);
  }

  // Atajo para imágenes
  public function image($src) {
    return $this->asset('images', $src);
  }

  // Favicons
  public function favicon($src) {
    return "{$this->domain}/uploads/site/favicons/{$src}";
  }

  // Logotipos
  public function logo($src) {
    return "{$this->domain}/uploads/site/{$src}";
  }

  // Rutas de páginas
  public function home() {
    return $this->domain;
  }

  public function signin() {
    return "{$this->domain}/signin";
  }

  public function signup() {
    return "{$this->domain}/signup";
  }

  public function signout() {
    return "{$this->domain}/signout";
  }

  public function error($error = 404) {
    return "{$this->domain}/{$error}";
  }

  public function page($page) {
    return "{$this->domain}/page/{$page}";
  }

  public function profile($action = null) {
    return $action
      ? "{$this->domain}/profile?action={$action}"
      : "{$this->domain}/profile";
  }
}
