<?php

session_start();

require_once "config.php";
require_once "functions.php";

// Conectar BD
$connect = connect();

if (!$connect) {
  header('Location: ' . APP_URL . '/admin/controller/error.php');
  exit();
}

if (isset($_SESSION["user_name"])) {
  $user_session = get_user_session_information($connect);
}
