<?php

/**
 * Logger
 *
 * Sistema de registro de eventos (logging) con niveles explícitos y organización
 * automática por subcarpetas. Permite capturar mensajes de información, advertencias,
 * errores y depuración, integrando contexto dinámico.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Logger {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE ESTADO
  // --------------------------------------------------------------------------

  protected string $basePath;
  protected string $message = '';
  protected string $level = 'INFO';
  protected array $context = [];
  protected ?string $scope = null;

  /**
   * Inicializa el logger definiendo la ruta base de almacenamiento.
   *
   * @param string $path Ruta física del directorio de logs.
   */
  public function __construct(string $path) {
    $this->basePath = rtrim($path, '/');

    if (!is_dir($this->basePath)) {
      mkdir($this->basePath, 0755, true);
    }
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Define una subcarpeta o ámbito específico para el log actual.
   *
   * @param string $scope Nombre de la subcarpeta.
   * @return self Instancia.
   */
  public function file(string $scope): self {
    $this->scope = trim($scope);
    return $this;
  }

  /**
   * Define un mensaje de nivel informativo.
   *
   * @param string $message Mensaje.
   * @return self Instancia.
   */
  public function info(string $message): self {
    return $this->setLevel('INFO', $message);
  }

  /**
   * Define un mensaje de nivel advertencia.
   *
   * @param string $message Mensaje.
   * @return self Instancia.
   */
  public function warning(string $message): self {
    return $this->setLevel('WARNING', $message);
  }

  /**
   * Define un mensaje de nivel error.
   *
   * @param string $message Mensaje.
   * @return self Instancia.
   */
  public function error(string $message): self {
    return $this->setLevel('ERROR', $message);
  }

  /**
   * Define un mensaje de nivel depuración.
   *
   * @param string $message Mensaje.
   * @return self Instancia.
   */
  public function debug(string $message): self {
    return $this->setLevel('DEBUG', $message);
  }

  /**
   * Añade datos de contexto adicionales al log.
   *
   * @param string $key Clave.
   * @param mixed $value Valor.
   * @return self Instancia.
   */
  public function with(string $key, mixed $value): self {
    $this->context[$key] = $this->normalize($value);
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: PROCESAMIENTO Y SALIDA
  // --------------------------------------------------------------------------

  /**
   * Escribe el mensaje acumulado en el archivo físico correspondiente.
   * Resetea el estado del logger tras la escritura.
   */
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

  // --------------------------------------------------------------------------
  // SECCIÓN: LÓGICA INTERNA
  // --------------------------------------------------------------------------

  /**
   * Configura el nivel y el mensaje del log.
   *
   * @param string $level Nivel (INFO, ERROR, etc).
   * @param string $message Mensaje.
   * @return self
   */
  protected function setLevel(string $level, string $message): self {
    $this->level   = $level;
    $this->message = $message;
    return $this;
  }

  /**
   * Detecta la ruta de la petición actual para el log.
   *
   * @return string Ruta o 'CLI'.
   */
  protected function currentRoute(): string {
    if (PHP_SAPI === 'cli') {
      return 'CLI';
    }

    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
  }

  /**
   * Normaliza valores complejos para ser guardados en el log.
   *
   * @param mixed $value Valor a normalizar.
   * @return mixed Valor normalizado.
   */
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

  /**
   * Restablece el estado interno del logger.
   */
  protected function reset(): void {
    $this->context = [];
    $this->level   = 'INFO';
    $this->message = '';
    $this->scope   = null;
  }
}