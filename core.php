<?php

if (!file_exists("config.php")) {
  header("Location: install/");
  exit();
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once "config.php";
require_once "libs/AccessControl.php";
require_once "libs/Encryption.php";
require_once "libs/MessageHandler.php";
require_once "libs/Log.php";
require_once "functions.php";

// Conectar BD
$connect = connect();

if (!$connect) {
  header('Location: ' . SITE_URL . '/admin/controller/error.php');
  exit();
}

if (isset($_SESSION["user_name"])) {
  $user_session = get_user_session_information($connect);
}

// Access Control
$accessControl = new AccessControl();

// Encryption
$encryption = new Encryption(ENCRYPT_METHOD, SECRET_KEY, SECRET_IV);

// Mensajes
$messageHandler = new MessageHandler();

// Obetener los logos
$querySelect = "SELECT * FROM brand";
$brand       = $connect->query($querySelect)->fetch(PDO::FETCH_OBJ);

// User log
$log = new Log($connect, BASE_DIR."/log/actions.log");
