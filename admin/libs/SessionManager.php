<?php
class SessionManager {
  private $connect;

  public function __construct($dbConnection) {
    $this->connect = $dbConnection;
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function isUserLoggedIn() {
    return isset($_SESSION['user_name']);
  }

  public function checkUserAccess() {
    if (!$this->isUserLoggedIn()) {
      $this->redirectWithMessage('/admin/controllers/login.php', 'No inició sesión', 'danger');
    }

    $access = $this->getUserRole();
    if ($access['user_role'] != 1 && $access['user_role'] != 0) {
      $this->redirectWithMessage('/', 'No eres administrador', 'danger');
    }

    return $access;
  }

  public function getUserRole() {
    // Implementa la lógica para obtener el rol del usuario
    return check_access($this->connect);
  }

  public function redirectWithMessage($url, $message, $type) {
    add_message($message, $type);
    header('Location: ' . APP_URL . $url);
    exit();
  }
}
