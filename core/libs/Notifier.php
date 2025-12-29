<?php

class Notifier {
  private string $message = '';
  private string $type = 'success';
  private string $method = 'bootstrap';

  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  /* ==========================================
   * FLUENT - Construcción del mensaje
   * ========================================== */

  public function message(string $message) {
    $this->message = $message;
    return $this;
  }

  public function success(?string $message = null) {
    if ($message !== null)
      $this->message = $message;
    $this->type = 'success';
    return $this;
  }

  public function danger(?string $message = null) {
    if ($message !== null)
      $this->message = $message;
    $this->type = 'danger';
    return $this;
  }

  public function warning(?string $message = null) {
    if ($message !== null)
      $this->message = $message;
    $this->type = 'warning';
    return $this;
  }

  public function info(?string $message = null) {
    if ($message !== null)
      $this->message = $message;
    $this->type = 'info';
    return $this;
  }

  public function bootstrap() {
    $this->method = 'bootstrap';
    return $this;
  }

  public function toast() {
    $this->method = 'toast';
    return $this;
  }

  public function sweetalert() {
    $this->method = 'sweetalert';
    return $this;
  }

  /* ==========================================
   * EJECUCIÓN
   * ========================================== */

  public function add() {
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

  private function reset() {
    $this->message = '';
    $this->type    = 'success';
    $this->method  = 'bootstrap';
  }

  /* ==========================================
   * CONSULTA (can())
   * ========================================== */

  public function can(): NotifierCan {
    return new NotifierCan();
  }

  /* ==========================================
   * RENDER
   * ========================================== */

  public function showBootstrap() {
    if (empty($_SESSION['bootstrap']))
      return;

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
        foreach ($messages as $m)
          echo "<li>{$m}</li>";
        echo "</ul>";
      }
      echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }

    unset($_SESSION['bootstrap']);
  }

  public function showToasts() {
    if (empty($_SESSION['toast']))
      return;

    echo "<script>";
    foreach ($_SESSION['toast'] as $t) {
      echo "Toastify({ text: \"{$t['message']}\", duration: 3000 }).showToast();";
    }
    echo "</script>";

    unset($_SESSION['toast']);
  }

  public function showSweetAlerts() {
    if (empty($_SESSION['sweetalert']))
      return;

    echo "<script>";
    foreach ($_SESSION['sweetalert'] as $a) {
      echo "Swal.fire({ text: \"{$a['message']}\", icon: \"{$a['type']}\" });";
    }
    echo "</script>";

    unset($_SESSION['sweetalert']);
  }
}

/* ==========================================================
 * OBJETO CONSULTOR (can())
 * ========================================================== */

class NotifierCan {
  private ?string $method = null;

  public function bootstrap() {
    $this->method = 'bootstrap';
    return $this;
  }

  public function toast() {
    $this->method = 'toast';
    return $this;
  }

  public function sweetalert() {
    $this->method = 'sweetalert';
    return $this;
  }

  public function danger(): bool {
    return $this->hasType('danger');
  }

  public function success(): bool {
    return $this->hasType('success');
  }

  public function warning(): bool {
    return $this->hasType('warning');
  }

  public function info(): bool {
    return $this->hasType('info');
  }

  public function any(): bool {
    $sources = $this->sources();
    foreach ($sources as $src) {
      if (!empty($_SESSION[$src]))
        return true;
    }
    return false;
  }

  private function hasType(string $type): bool {
    $sources = $this->sources();
    foreach ($sources as $src) {
      if (empty($_SESSION[$src]))
        continue;
      foreach ($_SESSION[$src] as $msg) {
        if ($msg['type'] === $type)
          return true;
      }
    }
    return false;
  }

  private function sources(): array {
    return $this->method
      ? [$this->method]
      : ['bootstrap', 'toast', 'sweetalert'];
  }
}
