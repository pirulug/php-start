<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


// require_once __DIR__ . "/libs/antixss/AntiXSS.php";

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/libs/SessionManager.php';

// Conectar BD
$connect = connect();

if (!$connect) {
  header('Location: ' . APP_URL . '/admin/controller/error.php');
  exit();
}

// Session Manager
$sessionManager = new SessionManager($connect);

// Obtener Informacion de usuario
if (isset($_SESSION["user_name"])) {
  $user_session = get_user_session_information($connect);
}