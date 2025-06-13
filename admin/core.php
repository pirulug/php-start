<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__."/../config.php";

require_once BASE_DIR . "/core/autoload.php";
require_once BASE_DIR . "/core/database.php";
require_once BASE_DIR . "/core/template.php";
require_once BASE_DIR . "/core/access.php";
require_once BASE_DIR . "/core/encryption.php";
require_once BASE_DIR . "/core/brand.php";
require_once BASE_DIR . "/core/settings.php";
require_once BASE_DIR . "/core/log.php";
require_once BASE_DIR . "/core/url_helper.php";
require_once BASE_DIR . "/core/visits.php";

