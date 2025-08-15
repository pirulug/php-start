<?php

class SiteUrl {
  private $base_url;

  public function __construct($base_url) {
    $this->base_url = rtrim($base_url, '/') . '/';
  }

  public function link($path = '') {
    return $this->base_url . ltrim($path, '/');
  }
}