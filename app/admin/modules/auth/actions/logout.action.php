<?php
// session_start();

// 1. Eliminar todas las variables de sesión
$_SESSION = [];

// 2. Eliminar la cookie de sesión (si existe)
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}

// 3. Destruir la sesión
session_destroy();

// 4. Eliminar la cookie de "recordarme"
if (isset($_COOKIE['php-start'])) {
  setcookie('php-start', '', time() - 3600, "/");
}

$log->message("Usuario ha cerrado sesión")
  ->type("info")
  ->write();

// 5. Redirigir al login
header("Location: " . admin_route("login"));
exit;
