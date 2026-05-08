<?php

/**
 * Notifier
 *
 * Sistema de notificaciones flash basado en sesiones.
 * Soporta múltiples métodos de visualización (Bootstrap, Toastify, SweetAlert)
 * y permite consultar el estado de las notificaciones de forma fluida.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Notifier {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE ESTADO
  // --------------------------------------------------------------------------

  private static ?Notifier $instance = null;

  private string $message = '';
  private string $type = 'success';
  private string $method = 'bootstrap';

  private bool $queryMode = false;
  private ?string $queryMethod = null;

  /**
   * Constructor: asegura que la sesión esté iniciada para persistir mensajes.
   */
  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    self::$instance = $this;
  }

  /**
   * Obtiene la instancia compartida del Notifier.
   *
   * @return self
   */
  public static function getInstance(): self {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Captura llamadas estáticas y las redirige a la instancia compartida.
   */
  public static function __callStatic($name, $arguments) {
    return self::getInstance()->$name(...$arguments);
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: GESTIÓN DE MENSAJES (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Define el cuerpo del mensaje de la notificación.
   *
   * @param string $message Texto a mostrar.
   * @return self Instancia.
   */
  public function message(string $message): self {
    $this->message = $message;
    return $this;
  }

  /**
   * Configura una notificación de éxito.
   *
   * @param string|null $message Mensaje opcional (acorta la llamada).
   * @return self|bool Instancia para encadenar o booleano si está en modo consulta.
   */
  public function success(?string $message = null) {
    return $this->handleType('success', $message);
  }

  /**
   * Configura una notificación de error/peligro.
   *
   * @param string|null $message Mensaje opcional.
   * @return self|bool Instancia o booleano.
   */
  public function danger(?string $message = null) {
    return $this->handleType('danger', $message);
  }

  /**
   * Configura una notificación de advertencia.
   *
   * @param string|null $message Mensaje opcional.
   * @return self|bool Instancia o booleano.
   */
  public function warning(?string $message = null) {
    return $this->handleType('warning', $message);
  }

  /**
   * Configura una notificación informativa.
   *
   * @param string|null $message Mensaje opcional.
   * @return self|bool Instancia o booleano.
   */
  public function info(?string $message = null) {
    return $this->handleType('info', $message);
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: MÉTODOS DE VISUALIZACIÓN
  // --------------------------------------------------------------------------

  /**
   * Define Bootstrap Alerts como método de salida.
   *
   * @return self Instancia.
   */
  public function bootstrap(): self {
    if ($this->queryMode) {
      $this->queryMethod = 'bootstrap';
      return $this;
    }
    $this->method = 'bootstrap';
    return $this;
  }

  /**
   * Define Toastify como método de salida.
   *
   * @return self Instancia.
   */
  public function toast(): self {
    if ($this->queryMode) {
      $this->queryMethod = 'toast';
      return $this;
    }
    $this->method = 'toast';
    return $this;
  }

  /**
   * Define SweetAlert2 como método de salida.
   *
   * @return self Instancia.
   */
  public function sweetalert(): self {
    if ($this->queryMode) {
      $this->queryMethod = 'sweetalert';
      return $this;
    }
    $this->method = 'sweetalert';
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: PERSISTENCIA Y CONSULTA
  // --------------------------------------------------------------------------

  /**
   * Registra la notificación en la sesión para ser mostrada en la siguiente carga.
   *
   * @return self Instancia.
   * @throws Exception Si no se ha definido un mensaje.
   */
  public function add(): self {
    if ($this->message === '') {
      throw new Exception('Notifier: mensaje requerido');
    }

    $_SESSION[$this->method][] = [
      'message' => $this->message,
      'type'    => $this->type
    ];

    $this->reset();
    return $this;
  }

  /**
   * Activa el modo de consulta para verificar si existen notificaciones.
   *
   * @return self Instancia.
   */
  public function can(): self {
    $this->queryMode   = true;
    $this->queryMethod = null;
    return $this;
  }

  /**
   * Verifica si existe cualquier notificación pendiente en cualquier canal.
   *
   * @return bool True si hay mensajes pendientes.
   */
  public function any(): bool {
    $sources = $this->querySources();
    $this->resetQuery();
    foreach ($sources as $src) {
      if (!empty($_SESSION[$src])) {
        return true;
      }
    }
    return false;
  }

  /**
   * Procesa internamente el tipo de notificación y la lógica de consulta.
   *
   * @param string $type Tipo (success, danger, etc).
   * @param string|null $message Mensaje.
   * @return self|bool
   */
  private function handleType(string $type, ?string $message) {
    if ($this->queryMode) {
      $sources = $this->querySources();
      $this->resetQuery();

      foreach ($sources as $src) {
        if (empty($_SESSION[$src])) {
          continue;
        }
        foreach ($_SESSION[$src] as $msg) {
          if ($msg['type'] === $type) {
            return true;
          }
        }
      }
      return false;
    }

    if ($message !== null) {
      $this->message = $message;
    }

    $this->type = $type;
    return $this;
  }

  /**
   * Resuelve los canales de notificación a consultar.
   *
   * @return array Listado de llaves de sesión.
   */
  private function querySources(): array {
    return $this->queryMethod
      ? [$this->queryMethod]
      : ['bootstrap', 'toast', 'sweetalert'];
  }

  /**
   * Restablece el estado de configuración del mensaje.
   */
  private function reset(): void {
    $this->message = '';
    $this->type    = 'success';
    $this->method  = 'bootstrap';
  }

  /**
   * Desactiva el modo de consulta.
   */
  private function resetQuery(): void {
    $this->queryMode   = false;
    $this->queryMethod = null;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: RENDERIZADO (VISTAS)
  // --------------------------------------------------------------------------

  /**
   * Renderiza las alertas de Bootstrap acumuladas en la sesión.
   */
  public function showBootstrap(): void {
    if (empty($_SESSION['bootstrap'])) {
      return;
    }

    $grouped = [];
    foreach ($_SESSION['bootstrap'] as $msg) {
      $grouped[$msg['type']][] = $msg['message'];
    }

    foreach ($grouped as $type => $messages) {
      echo "<div class='alert alert-{$type} alert-dismissible fade show'>";
      if (count($messages) === 1) {
        echo $messages[0];
      } else {
        echo "<ul class='mb-0'>";
        foreach ($messages as $m) {
          echo "<li>{$m}</li>";
        }
        echo "</ul>";
      }
      echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }

    unset($_SESSION['bootstrap']);
  }

  /**
   * Renderiza las notificaciones de Toastify mediante inyección JS.
   */
  public function showToasts(): void {
    if (empty($_SESSION['toast'])) {
      return;
    }

    echo "<script>";
    foreach ($_SESSION['toast'] as $t) {
      echo "Toastify({ text: \"{$t['message']}\", duration: 3000 }).showToast();";
    }
    echo "</script>";

    unset($_SESSION['toast']);
  }

  /**
   * Renderiza las alertas de SweetAlert2 mediante inyección JS.
   */
  public function showSweetAlerts(): void {
    if (empty($_SESSION['sweetalert'])) {
      return;
    }

    echo "<script>";
    foreach ($_SESSION['sweetalert'] as $a) {
      echo "Swal.fire({ text: \"{$a['message']}\", icon: \"{$a['type']}\" });";
    }
    echo "</script>";

    unset($_SESSION['sweetalert']);
  }
}