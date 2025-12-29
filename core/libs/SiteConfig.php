<?php

class SiteConfig {
  private PDO $db;
  private ?object $data = null;
  private bool $loaded = false;

  public function __construct(PDO $db) {
    $this->db = $db;
  }

  /**
   * Carga lazy + cache
   */
  private function load(): void {
    if ($this->loaded) {
      return;
    }

    $data = new stdClass();

    $stmt = $this->db->prepare(
      "SELECT option_key, option_value FROM options"
    );
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($rows as $row) {

      $value = $row->option_value;

      // JSON → OBJ automático
      if (is_string($value)) {
        $json = json_decode($value);
        if (json_last_error() === JSON_ERROR_NONE) {
          $value = $json;
        }
      }

      $data->{$row->option_key} = $value;
    }

    $this->data   = $data;
    $this->loaded = true;
  }

  public function get(string $key, mixed $default = null): mixed {
    $this->load();

    return $this->data->{$key} ?? $default;
  }

  /* =========================
   * ACCESOS SIMPLES (FLUENT)
   * ========================= */

  public function siteName(): ?string {
    $this->load();
    return $this->data->site_name ?? null;
  }

  public function siteUrl(): ?string {
    $this->load();
    return $this->data->site_url ?? null;
  }

  public function timezone(): ?string {
    $this->load();
    return $this->data->site_timezone ?? null;
  }

  public function title(?string $pageTitle = null): string {
    $site = $this->siteName() ?? APP_NAME;

    if ($pageTitle) {
      return $pageTitle . ' | ' . $site;
    }

    return $site;
  }


  /* =========================
   * FAVICON
   * ========================= */

  public function favicon(): ?object {
    $this->load();
    return $this->data->favicon ?? null;
  }

  /* =========================
   * LOGOS
   * ========================= */

  public function logo(): object {
    $this->load();

    return (object) [
      'dark'  => $this->data->dark_logo ?? null,
      'light' => $this->data->white_logo ?? null,
    ];
  }

  /* =========================
   * RESET CACHE
   * ========================= */

  public function refresh(): self {
    $this->loaded = false;
    $this->data   = null;
    return $this;
  }
}
