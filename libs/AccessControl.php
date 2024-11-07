<?php

class AccessControl {
  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Verificar si el usuario está logeado.
   *
   * @return bool
   */
  public function is_user_logged_in() {
    return isset($_SESSION['signedin']) && $_SESSION['signedin'] === true;
  }

  /**
   * Redirige si el usuario no está logeado.
   *
   * @param string $redirect URL de redirección si no está logeado.
   * @return void
   */
  public function require_login($redirect = "/login.php") {
    if (!$this->is_user_logged_in()) {
      header("Location: " . $redirect);
      exit();
    }
  }

  /**
   * Verificar si el usuario tiene el rol necesario.
   *
   * @param array|string $allowedRoles Lista de roles permitidos.
   * @return bool
   */
  public function user_has_role($allowedRoles) {
    if (!$this->is_user_logged_in())
      return false;
    $userRole = $_SESSION['user_role'];
    if (is_array($allowedRoles)) {
      return in_array($userRole, $allowedRoles);
    }
    return $userRole === $allowedRoles;
  }

  /**
   * Verificar acceso a una sección específica.
   *
   * @param array|string $allowedRoles Roles permitidos para la sección.
   * @param string $redirect URL a redirigir si el acceso está denegado.
   * @return void
   */
  public function check_access($allowedRoles, $redirect = "/access_denied.php") {
    if (!$this->user_has_role($allowedRoles)) {
      header("Location: " . $redirect);
      exit();
    }
  }

  /**
   * Obtener el rol actual del usuario.
   *
   * @return string|null Retorna el rol si está logeado, de lo contrario null.
   */
  public function get_user_role() {
    return $_SESSION['user_role'] ?? null;
  }

  /**
   * Obtener el ID actual del usuario.
   *
   * @return string|null Retorna el ID del usuario si está logeado, de lo contrario null.
   */
  public function get_user_id() {
    return $_SESSION['user_id'] ?? null;
  }

  public function hasAccess($allowedRoles, $user_role) {
    return in_array($user_role, $allowedRoles);
  }
}
