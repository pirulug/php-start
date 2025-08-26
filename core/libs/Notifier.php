<?php
class Notifier {

  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Agregar una notificación a la sesión
   * @param string $message Texto del mensaje
   * @param string $type success | danger | primary | warning ...
   * @param string $method bootstrap | sweetalert | toast (se permiten alias: bs, sa, tf)
   */
  public function add($message, $type = 'success', $method = 'bootstrap') {
    // Alias para métodos
    $aliases = [
      'bs'       => 'bootstrap',
      'sa'       => 'sweetalert',
      'tf'       => 'toast',
      'toastify' => 'toast',
    ];

    $method = strtolower($method);
    if (isset($aliases[$method])) {
      $method = $aliases[$method];
    }

    switch ($method) {
      case 'bootstrap':
        $this->storeBootstrap($message, $type);
        break;
      case 'sweetalert':
        $this->storeSweetAlert($message, $type);
        break;
      case 'toast':
        $this->storeToast($message, $type);
        break;
      default:
        throw new Exception("Método de notificación no soportado: $method");
    }
  }

  /** ---------- Métodos privados de almacenamiento ---------- */

  private function storeBootstrap($message, $type) {
    $_SESSION['bootstrap'][] = ['message' => $message, 'type' => $type];
  }

  private function storeSweetAlert($message, $type) {
    $_SESSION['sweetalert'][] = ['message' => $message, 'type' => $type];
  }

  private function storeToast($message, $type) {
    $_SESSION['toast'][] = ['message' => $message, 'type' => $type];
  }

  /** ---------- Métodos de visualización ---------- */

  public function showBootstrap() {
    if (!empty($_SESSION['bootstrap'])) {
      $messages = $_SESSION['bootstrap'];

      foreach ($messages as $msg) {
        echo "<div class='alert alert-{$msg['type']} alert-dismissible fade show' role='alert'>
                {$msg['message']}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
      }

      unset($_SESSION['bootstrap']);
    }
  }

  public function showToasts() {
    if (!empty($_SESSION['toast'])) {
      echo "<script>";
      foreach ($_SESSION['toast'] as $toast) {
        $bgColor = $this->getToastColor($toast['type']);
        echo "Toastify({
                text: \"{$toast['message']}\",
                close: true,
                duration: 3000,
                style: { background: \"$bgColor\" }
              }).showToast();";
      }
      echo "</script>";
      unset($_SESSION['toast']);
    }
  }

  public function showSweetAlerts() {
    if (!empty($_SESSION['sweetalert'])) {
      echo "<script>";
      foreach ($_SESSION['sweetalert'] as $alert) {
        $icon = $this->getSweetAlertIcon($alert['type']);
        echo "Swal.fire({
                text: \"{$alert['message']}\",
                icon: \"$icon\",
                confirmButtonText: 'Aceptar'
              });";
      }
      echo "</script>";
      unset($_SESSION['sweetalert']);
    }
  }

  /** ---------- Utilidades ---------- */

  public function has($method = 'bootstrap', $type = null) {
    $messages = $_SESSION[$method] ?? [];
    if ($type) {
      foreach ($messages as $msg) {
        if ($msg['type'] === $type)
          return true;
      }
      return false;
    }
    return !empty($messages);
  }

  private function getSweetAlertIcon($type) {
    $icons = [
      'success' => 'success',
      'danger'  => 'error',
      'warning' => 'warning',
      'info'    => 'info',
      'primary' => 'question',
    ];
    return $icons[$type] ?? 'info';
  }

  private function getToastColor($type) {
    $colors = [
      'info'      => 'linear-gradient(to right, #00b09b, #96c93d)',
      'success'   => 'linear-gradient(to right, #00b09b, #96c93d)',
      'danger'    => 'linear-gradient(to right, #ff5f6d, #ffc371)',
      'warning'   => 'linear-gradient(to right, #f7b733, #fc4a1a)',
      'primary'   => 'linear-gradient(to right, #3498db, #2980b9)',
      'secondary' => 'linear-gradient(to right, #95a5a6, #7f8c8d)',
      'light'     => 'linear-gradient(to right, #ecf0f1, #bdc3c7)',
      'dark'      => 'linear-gradient(to right, #34495e, #2c3e50)',
    ];
    return $colors[$type] ?? $colors['info'];
  }
}
