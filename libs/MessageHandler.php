<?php
class MessageHandler {

  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  // Agregar un mensaje con opción de tipo de notificación
  public function addMessage($message, $type = 'success', $method = 'bootstrap') {
    if ($method === 'toast') {
      $this->addToast($message, $type);
    } else {
      $this->addBootstrapMessage($message, $type);
    }
  }

  // Agregar un mensaje de Bootstrap
  private function addBootstrapMessage($message, $type) {
    if (!isset($_SESSION['messages'])) {
      $_SESSION['messages'] = [];
    }
    $_SESSION['messages'][] = ['message' => $message, 'type' => $type];
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


// Modo de uso

// $messageHandler = new MessageHandler();

// Agregar mensajes de Bootstrap
/* $messageHandler->addMessage("Este es un mensaje de alerta primaria", "primary", "bootstrap");
$messageHandler->addMessage("Este es un mensaje de alerta secundaria", "secondary", "bootstrap");
$messageHandler->addMessage("Este es un mensaje de alerta de éxito", "success", "bootstrap");
$messageHandler->addMessage("Este es un mensaje de alerta de peligro", "danger", "bootstrap");
$messageHandler->addMessage("Este es un mensaje de alerta de advertencia", "warning", "bootstrap");
$messageHandler->addMessage("Este es un mensaje de alerta informativa", "info", "bootstrap");
$messageHandler->addMessage("Este es un mensaje de alerta ligera", "light", "bootstrap");
$messageHandler->addMessage("Este es un mensaje de alerta oscura", "dark", "bootstrap"); */

// Agregar notificaciones Toastify
/* $messageHandler->addMessage("Este es un Toastify de información", "info", "toast");
$messageHandler->addMessage("Este es un Toastify de éxito", "success", "toast");
$messageHandler->addMessage("Este es un Toastify de peligro", "danger", "toast");
$messageHandler->addMessage("Este es un Toastify de advertencia", "warning", "toast");
$messageHandler->addMessage("Este es un Toastify de primaria", "primary", "toast");
$messageHandler->addMessage("Este es un Toastify de secundaria", "secondary", "toast");
$messageHandler->addMessage("Este es un Toastify de ligera", "light", "toast");
$messageHandler->addMessage("Este es un Toastify de oscura", "dark", "toast"); */

// Verificar y realizar acciones según el tipo de mensaje
/* if ($messageHandler->hasMessagesOfType('danger')) {
  echo "<p>Hay mensajes de tipo 'danger' en la sesión.</p>";
}

if ($messageHandler->hasMessagesOfType('success')) {
  echo "<p>Hay mensajes de tipo 'success' en la sesión.</p>";
}

if ($messageHandler->hasMessagesOfType('warning')) {
  echo "<p>Hay mensajes de tipo 'warning' en la sesión.</p>";
}

if ($messageHandler->hasMessagesOfType('info')) {
  echo "<p>Hay mensajes de tipo 'info' en la sesión.</p>";
}

if ($messageHandler->hasMessagesOfType('primary')) {
  echo "<p>Hay mensajes de tipo 'primary' en la sesión.</p>";
}

if ($messageHandler->hasMessagesOfType('secondary')) {
  echo "<p>Hay mensajes de tipo 'secondary' en la sesión.</p>";
}

if ($messageHandler->hasMessagesOfType('light')) {
  echo "<p>Hay mensajes de tipo 'light' en la sesión.</p>";
}

if ($messageHandler->hasMessagesOfType('dark')) {
  echo "<p>Hay mensajes de tipo 'dark' en la sesión.</p>";
} */

// Mostrar los mensajes de Bootstrap
// $messageHandler->displayMessages();

// Mostrar las notificaciones Toastify
// $messageHandler->displayToasts();

