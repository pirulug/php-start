<?php

require_once BASE_DIR . "/core/libraries/DataBase.php";
require_once BASE_DIR . "/core/libraries/Cipher.php";
require_once BASE_DIR . "/core/libraries/SiteConfig.php";

// Configuracion de la base de datos
$db = (new DataBase())
  ->host(DB_HOST)
  ->name(DB_NAME)
  ->user(DB_USER)
  ->password(DB_PASS);

$connect = $db->getConnection();

// Cipher
$cipher = (new Cipher())
  ->method(ENCRYPT_METHOD)
  ->secretkey(ENCRYPT_KEY)
  ->secretiv(ENCRYPT_IV);

// Configuración del sitio
$config = new SiteConfig($connect);

// Sona Horaria
date_default_timezone_set(
  $config->get('site_timezone', 'America/Lima')
);