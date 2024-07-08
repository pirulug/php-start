<?php

require_once "core.php";

session_start();

session_destroy();
$_SESSION = array ();

header('Location: /');
