<?php

if (!file_exists("config.php")) {
  header("Location: install/");
  exit();
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . "config.php";

require_once __DIR__ . "/core/autoload.php";
require_once __DIR__ . "/core/database.php";
require_once __DIR__ . "/core/template.php";
require_once __DIR__ . "/core/access.php";
require_once __DIR__ . "/core/encryption.php";
require_once __DIR__ . "/core/brand.php";
require_once __DIR__ . "/core/settings.php";
require_once __DIR__ . "/core/log.php";
require_once __DIR__ . "/core/url_helper.php";
require_once __DIR__ . "/core/visits.php";
