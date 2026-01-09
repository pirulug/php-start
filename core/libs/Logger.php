<?php

/**
 * Logger
 *
 * Sistema de logging con niveles explícitos y
 * subcarpetas automáticas por dominio.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Logger {

  protected string $basePath;
  protected string $message = '';
  protected string $level = 'INFO';
  protected array $context = [];
  protected ?string $scope = null;

  public function __construct(string $path) {
    $this->basePath = rtrim($path, '/');

    if (!is_dir($this->basePath)) {
      mkdir($this->basePath, 0755, true);
    }
  }

  public function file(string $scope): self {
    $this->scope = trim($scope);
    return $this;
  }

  public function info(string $message): self {
    return $this->setLevel('INFO', $message);
  }

  public function warning(string $message): self {
    return $this->setLevel('WARNING', $message);
  }

  public function error(string $message): self {
    return $this->setLevel('ERROR', $message);
  }

  public function debug(string $message): self {
    return $this->setLevel('DEBUG', $message);
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

    $dir = $this->scope
      ? $this->basePath . '/' . $this->scope
      : $this->basePath;

    if (!is_dir($dir)) {
      mkdir($dir, 0755, true);
    }

    $filePath = $dir . '/' . $day . '.log';

    $line = sprintf(
      '[%s] [%s] [%s] [%s] %s %s%s',
      $dateTime,
      $this->level,
      $ip,
      $route,
      $this->message,
      $this->context ? json_encode($this->context, JSON_UNESCAPED_UNICODE) : '',
      PHP_EOL
    );

    file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);

    $this->reset();
  }

  protected function setLevel(string $level, string $message): self {
    $this->level   = $level;
    $this->message = $message;
    return $this;
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
    $this->level   = 'INFO';
    $this->message = '';
    $this->scope   = null;
  }
}
