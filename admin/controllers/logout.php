<?php

require_once "../core.php";

session_start();

// Registrar la acción de cierre de sesión en el log
$log->logAction($_SESSION['user_id'], 'Salir', $_SESSION['user_name'] . " salió.");

// Destruir la sesión y limpiar el array de sesiones
session_destroy();
$_SESSION = array();

// Eliminar la cookie 'loggin' estableciendo un tiempo de expiración pasado
if (isset($_COOKIE['psloggin'])) {
  setcookie('psloggin', '', time() - 3600, "/");
}

// Redirigir al usuario a la página de inicio de sesión
header('Location: ./login.php');
exit();
