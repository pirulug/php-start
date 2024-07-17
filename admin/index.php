<?php

require 'core.php';

// Session Manager
$sessionManager = new SessionManager($connect);

if ($sessionManager->isUserLoggedIn()) {
  $check_access = $sessionManager->getUserRole();

  if ($check_access['user_role'] == 0) {
    $sessionManager->redirectWithMessage('/admin/controllers/dashboard.php', 'Super Administrador', 'success');
  } elseif ($check_access['user_role'] == 1) {
    $sessionManager->redirectWithMessage('/admin/controllers/dashboard.php', 'Administrador', 'success');
  } else {
    $sessionManager->redirectWithMessage('/', 'No eres administrador', 'danger');
  }
} else {
  $sessionManager->redirectWithMessage('/admin/controllers/login.php', 'No inició sesión', 'danger');
}
