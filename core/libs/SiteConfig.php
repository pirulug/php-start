<?php

class SiteConfig {
  private PDO $db;
  private array $config = [];
  private bool $loaded = false;

  public function __construct(PDO $db) {
    $this->db = $db;
  }

  /**
   * Cargar configuraciones desde la BD (solo una vez).
   */
  private function load(): void {
    if ($this->loaded) {
      return;
    }

    $stmt         = $this->db->query("SELECT option_key, option_value FROM options");
    $this->config = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
    $this->loaded = true;
  }

  /**
   * Obtener valor de configuración.
   */
  public function get(string $key, $default = null) {
    $this->load();
    return $this->config[$key] ?? $default;
  }

  /**
   * Devolver todas las configuraciones.
   */
  public function all(): array {
    $this->load();
    return $this->config;
  }

  /**
   * Refrescar cache (si cambia algo en BD).
   */
  public function refresh(): void {
    $this->loaded = false;
    $this->load();
  }
}
