<?php

class Logger {
  protected string $path;

  protected string $message = '';
  protected string $type = 'INFO';
  protected array $context = [];

  /**
   * Constructor
   */
  public function __construct(string $path) {
    $this->path = rtrim($path, '/');

    if (!is_dir($this->path)) {
      mkdir($this->path, 0755, true);
    }
  }

  /**
   * Mensaje
   */
  public function message(string $message): self {
    $this->reset();
    $this->message = $message;
    return $this;
  }

  /**
   * Tipo: error | success | warning | info | debug
   */
  public function type(string $type): self {
    $this->type = strtoupper($type);
    return $this;
  }

  /**
   * Contexto opcional
   */
  public function with(string $key, mixed $value): self {
    $this->context[$key] = $value;
    return $this;
  }

  /**
   * Escribir log
   */
  public function write(): void {
    $dateTime = date('Y-m-d H:i:s');
    $day      = date('Y-m-d');
    $route    = $this->currentRoute();
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'CLI';

    $file = "{$this->path}/{$day}.log";

    $line = sprintf(
      "[%s] [%s] [%s] %s %s%s",
      $dateTime,
      $this->type,
      $route,
      $this->message,
      !empty($this->context)
      ? json_encode($this->context, JSON_UNESCAPED_UNICODE)
      : '',
      PHP_EOL
    );

    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);

    $this->reset();
  }

  /**
   * Ruta actual
   */
  protected function currentRoute(): string {
    if (!isset($_SERVER['REQUEST_URI'])) {
      return 'CLI';
    }

    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
  }

  /**
   * Reset interno
   */
  protected function reset(): void {
    $this->context = [];
    $this->type    = 'INFO';
  }
}
