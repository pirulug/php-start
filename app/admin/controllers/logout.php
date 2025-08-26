<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Destruir la sesión y limpiar el array de sesiones
session_destroy();
$_SESSION = array();

// Eliminar la cookie 'loggin' estableciendo un tiempo de expiración pasado
if (isset($_COOKIE['psloggin'])) {
  setcookie('psloggin', '', time() - 3600, "/");
}

// Redirigir al usuario a la página de inicio de sesión
header('Location: ' . url_admin("login"));
exit();
