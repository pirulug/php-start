<?php

class Notifier {

  private string $message = '';
  private string $type = 'success';
  private string $method = 'bootstrap';

  private bool $queryMode = false;
  private ?string $queryMethod = null;

  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function message(string $message): self {
    $this->message = $message;
    return $this;
  }

  public function success(?string $message = null) {
    return $this->handleType('success', $message);
  }

  public function danger(?string $message = null) {
    return $this->handleType('danger', $message);
  }

  public function warning(?string $message = null) {
    return $this->handleType('warning', $message);
  }

  public function info(?string $message = null) {
    return $this->handleType('info', $message);
  }

  public function bootstrap(): self {
    if ($this->queryMode) {
      $this->queryMethod = 'bootstrap';
      return $this;
    }
    $this->method = 'bootstrap';
    return $this;
  }

  public function toast(): self {
    if ($this->queryMode) {
      $this->queryMethod = 'toast';
      return $this;
    }
    $this->method = 'toast';
    return $this;
  }

  public function sweetalert(): self {
    if ($this->queryMode) {
      $this->queryMethod = 'sweetalert';
      return $this;
    }
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
    $this->queryMode   = true;
    $this->queryMethod = null;
    return $this;
  }

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

  private function querySources(): array {
    return $this->queryMethod
      ? [$this->queryMethod]
      : ['bootstrap', 'toast', 'sweetalert'];
  }

  private function reset(): void {
    $this->message = '';
    $this->type    = 'success';
    $this->method  = 'bootstrap';
  }

  private function resetQuery(): void {
    $this->queryMode   = false;
    $this->queryMethod = null;
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
}
