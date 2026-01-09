<?php

/**
 * Notifier
 *
 * Clase encargada de la gestión y envío de notificaciones del sistema.
 * Permite generar mensajes informativos, alertas y avisos al usuario
 * a través de distintos canales de comunicación.
 *
 * Facilita la centralización de notificaciones internas, eventos
 * del sistema y respuestas visuales o programáticas.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class Notifier {

  private string $message = '';
  private string $type = 'success';
  private string $method = 'bootstrap';
  private ?string $canMethod = null;

  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function message(string $message): self {
    $this->message = $message;
    return $this;
  }

  public function success(?string $message = null): self {
    if ($message !== null) {
      $this->message = $message;
    }
    $this->type = 'success';
    return $this;
  }

  public function danger(?string $message = null): self {
    if ($message !== null) {
      $this->message = $message;
    }
    $this->type = 'danger';
    return $this;
  }

  public function warning(?string $message = null): self {
    if ($message !== null) {
      $this->message = $message;
    }
    $this->type = 'warning';
    return $this;
  }

  public function info(?string $message = null): self {
    if ($message !== null) {
      $this->message = $message;
    }
    $this->type = 'info';
    return $this;
  }

  public function bootstrap(): self {
    $this->method = 'bootstrap';
    return $this;
  }

  public function toast(): self {
    $this->method = 'toast';
    return $this;
  }

  public function sweetalert(): self {
    $this->method = 'sweetalert';
    return $this;
  }

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

  public function can(): self {
    $this->canMethod = null;
    return $this;
  }

  public function any(): bool {
    foreach ($this->sources() as $src) {
      if (!empty($_SESSION[$src])) {
        return true;
      }
    }
    return false;
  }

  public function has(string $type): bool {
    foreach ($this->sources() as $src) {
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

  private function reset(): void {
    $this->message = '';
    $this->type    = 'success';
    $this->method  = 'bootstrap';
  }

  private function sources(): array {
    return $this->canMethod
      ? [$this->canMethod]
      : ['bootstrap', 'toast', 'sweetalert'];
  }
}
