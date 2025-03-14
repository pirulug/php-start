<?php
class MessageHandler {

  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Agregar un mensaje con opción de tipo de notificación
   * @param mixed $message Añade el mensaje
   * @param mixed $type success | danger | primary ...
   * @param mixed $method Tipo de notificación: 'bs' para Bootstrap, 'sa' para SweetAlert2, 'tf' para Toastify
   * @return void
   */
  public function addMessage($message, $type = 'success', $method = 'bs') {
    if ($method === "toast") {
      $this->addToast($message, $type);
    } elseif ($method === "sweetalert") {
      $this->addSweetAlert($message, $type);
    } elseif ($method === "bootstrap") {
      $this->addBootstrap($message, $type);
    }

    if ($method === 'tf') {
      $this->addToast($message, $type);
    } elseif ($method === 'sa') {
      $this->addSweetAlert($message, $type);
    } elseif ($method === 'bs') {
      $this->addBootstrap($message, $type);
    }
  }

  // Agregar un mensaje de Bootstrap
  private function addBootstrap($message, $type) {
    if (!isset($_SESSION['messages'])) {
      $_SESSION['messages'] = [];
    }
    $_SESSION['messages'][] = ['message' => $message, 'type' => $type];
  }

  // Agregar una notificación SweetAlert
  private function addSweetAlert($message, $type) {
    if (!isset($_SESSION['sweetalerts'])) {
      $_SESSION['sweetalerts'] = [];
    }
    $_SESSION['sweetalerts'][] = ['message' => $message, 'type' => $type];
  }

  // Agregar una notificación Toastify
  private function addToast($message, $type) {
    if (!isset($_SESSION['toasts'])) {
      $_SESSION['toasts'] = [];
    }
    $_SESSION['toasts'][] = ['message' => $message, 'type' => $type];
  }

  // Mostrar los mensajes de Bootstrap
  public function displayMessages() {
    if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])) {
      $messages      = $_SESSION['messages'];
      $messageGroups = [
        'primary'   => [],
        'secondary' => [],
        'success'   => [],
        'danger'    => [],
        'warning'   => [],
        'info'      => [],
        'light'     => [],
        'dark'      => [],
      ];

      // Clasificar mensajes por tipo
      foreach ($messages as $msg) {
        if (array_key_exists($msg['type'], $messageGroups)) {
          $messageGroups[$msg['type']][] = $msg['message'];
        } else {
          $messageGroups['success'][] = $msg['message'];
        }
      }

      // Mostrar los mensajes por tipo
      foreach ($messageGroups as $type => $msgs) {
        if (!empty($msgs)) {
          echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'><ul class='mb-0'>";
          foreach ($msgs as $message) {
            echo "<li>$message</li>";
          }
          echo "</ul>
          <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
          </div>";
        }
      }

      unset($_SESSION['messages']);
    }
  }

  // Mostrar las notificaciones Toastify
  public function displayToasts() {
    if (isset($_SESSION['toasts']) && !empty($_SESSION['toasts'])) {
      echo "<script>";
      foreach ($_SESSION['toasts'] as $toast) {
        $bgColor = $this->getBackgroundColor($toast['type']);
        echo "Toastify({
                text: \"{$toast['message']}\",
                close: true,
                duration: 3000,
                style: {
                  background: \"$bgColor\",
                }
              }).showToast();";
      }
      echo "</script>";

      unset($_SESSION['toasts']);
    }
  }

  // Mostrar las notificaciones SweetAlert2
  public function displaySweetAlerts() {
    if (isset($_SESSION['sweetalerts']) && !empty($_SESSION['sweetalerts'])) {
      echo "<script>";
      foreach ($_SESSION['sweetalerts'] as $alert) {
        $icon = $this->getSweetAlertIcon($alert['type']);
        echo "Swal.fire({
                text: \"{$alert['message']}\",
                icon: \"$icon\",
                confirmButtonText: 'Aceptar'
              });";
      }
      echo "</script>";

      unset($_SESSION['sweetalerts']);
    }
  }

  // Verificar si existen mensajes de un tipo específico
  public function hasMessagesOfType($type = 'danger') {
    if (isset($_SESSION['messages'])) {
      foreach ($_SESSION['messages'] as $msg) {
        if ($msg['type'] == $type) {
          return true;
        }
      }
    }
    return false;
  }

  // Verificar si existen mensajes
  public function hasMessages() {
    return isset($_SESSION['messages']) && !empty($_SESSION['messages']);
  }

  // Obtener el icono correspondiente para SweetAlert2
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

  // Obtener el color de fondo basado en el tipo de Toastify
  private function getBackgroundColor($type) {
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
