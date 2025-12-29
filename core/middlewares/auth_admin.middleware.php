<?php

function auth_admin_middleware(array $route) {
  if (!isset($_SESSION['signin'])) {

    // Guardar la URL solicitada (solo GET)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    }

    header('Location: ' . APP_URL . "/" . PATH_ADMIN . '/login');
    exit;
  }
}
