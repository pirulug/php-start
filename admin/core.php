<?php

session_start();

// require_once __DIR__ . "/libs/antixss/AntiXSS.php";

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';

// Conectar BD
$connect = connect();

if (!$connect) {
  header('Location: ' . APP_URL . '/admin/controller/error.php');
  exit();
}
