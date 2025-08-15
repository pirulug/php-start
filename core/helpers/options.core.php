<?php

class SiteOptions {
  private $connect;
  private $options = [];

  public function __construct(PDO $connect) {
    $this->connect = $connect;
    $this->loadOptions();
    $this->defineConstants();
  }

  private function loadOptions() {
    $query = "SELECT option_key, option_value FROM options";
    $stmt  = $this->connect->prepare($query);
    $stmt->execute();
    $options_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($options_raw as $row) {
      $this->options[$row['option_key']] = $row['option_value'];
    }
  }

  private function defineConstants() {
    define('SITE_NAME', $this->options['site_name'] ?? 'Php Start');
    define('SITE_URL', $this->options['site_url'] ?? 'http://php-start.test');
    define('SITE_URL_ADMIN', (SITE_URL . '/panel'));
    define('SITE_DESCRIPTION', $this->options['site_description'] ?? '');
    define('SITE_KEYWORDS', $this->options['site_keywords'] ?? '');
  }

  public function getOption(string $key, $default = null) {
    return $this->options[$key] ?? $default;
  }

  public function getFavicon(string $type) {
    $favicons = json_decode($this->options['favicon'] ?? '{}', true);
    return $favicons[$type] ?? null;
  }

  public function getDarkLogo() {
    return $this->getOption('dark_logo', 'default-dark.png');
  }

  public function getWhiteLogo() {
    return $this->getOption('white_logo', 'default-white.png');
  }

  public function getOgImage() {
    return $this->getOption('og_image', 'default-og.png');
  }
}

