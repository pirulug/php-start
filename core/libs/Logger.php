<?php

/**
 * Logger
 *
 * Clase encargada del registro y gestión de logs del sistema.
 * Permite almacenar mensajes informativos, advertencias y errores,
 * facilitando la depuración, auditoría y seguimiento de eventos.
 *
 * Soporta distintos niveles de log y formatos de salida.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Logger {

  protected string $path;
  protected string $message = '';
  protected string $type = 'INFO';
  protected array $context = [];

  protected array $levels = [
    'ERROR',
    'SUCCESS',
    'WARNING',
    'INFO',
    'DEBUG'
  ];

  public function __construct(string $path) {
    $this->path = rtrim($path, '/');

    if (!is_dir($this->path)) {
      mkdir($this->path, 0755, true);
    }
  }

  public function message(string $message): self {
    $this->reset();
    $this->message = $message;
    return $this;
  }

  public function type(string $type): self {
    $type       = strtoupper($type);
    $this->type = in_array($type, $this->levels, true) ? $type : 'INFO';
    return $this;
  }

  public function with(string $key, mixed $value): self {
    $this->context[$key] = $this->normalize($value);
    return $this;
  }

  public function write(): void {

    $dateTime = date('Y-m-d H:i:s');
    $day      = date('Y-m-d');
    $route    = $this->currentRoute();
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'CLI';

    $file = $this->path . '/' . $day . '.log';

    $line = sprintf(
      '[%s] [%s] [%s] [%s] %s %s%s',
      $dateTime,
      $this->type,
      $ip,
      $route,
      $this->message,
      $this->context ? json_encode($this->context, JSON_UNESCAPED_UNICODE) : '',
      PHP_EOL
    );

    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);

    $this->reset();
  }

  protected function currentRoute(): string {
    if (PHP_SAPI === 'cli') {
      return 'CLI';
    }

    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
  }

  protected function normalize(mixed $value): mixed {

    if (is_resource($value)) {
      return 'RESOURCE';
    }

    if ($value instanceof Throwable) {
      return [
        'exception' => get_class($value),
        'message'   => $value->getMessage(),
        'file'      => $value->getFile(),
        'line'      => $value->getLine()
      ];
    }

    return $value;
  }

  protected function reset(): void {
    $this->context = [];
    $this->type    = 'INFO';
  }
}
