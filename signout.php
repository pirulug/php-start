<?php

require_once "core.php";

session_start();

$log->logAction($_SESSION['user_id'], 'Salir', $_SESSION['user_name'] . " salió.");

session_destroy();
$_SESSION = array ();

if (isset($_COOKIE['psloggin'])) {
  setcookie('psloggin', '', time() - 3600, "/");
}

header('Location: /');
