<?php

require_once "../core.php";

session_start();

$log->logAction($_SESSION['user_id'], 'Salir', $_SESSION['user_name'] . " Salio.");

session_destroy();

$_SESSION = array();

header('Location: ./login.php');
