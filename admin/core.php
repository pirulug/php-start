<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';

// Libs
require_once BASE_DIR . '/libs/Database.php';
require_once BASE_DIR . '/libs/AccessControl.php';
require_once BASE_DIR . '/libs/Encryption.php';
require_once BASE_DIR . '/libs/MessageHandler.php';
require_once BASE_DIR . '/libs/Log.php';

// Conectar BD
$db      = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$connect = $db->getConnection();

if (!$connect) {
  header('Location: ' . SITE_URL . '/admin/controller/error.php');
  exit();
}

// Obtener Informacion de usuario
if (isset($_SESSION["user_name"])) {
  $user_session = get_user_session_information($connect);
}

// Access Control
$accessControl = new AccessControl();

// Encryption
$encryption = new Encryption(ENCRYPT_METHOD, SECRET_KEY, SECRET_IV);

// Mensajes
$messageHandler = new MessageHandler();

// User log
$log = new Log($connect, BASE_DIR."/log/actions.log");