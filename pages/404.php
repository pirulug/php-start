<?php

require_once "core.php";

if (isset($_SESSION['access_message'])) {
  echo "<p>{$_SESSION['access_message']}</p>";
  unset($_SESSION['access_message']);
}

$page_title       = "404 | " . $settings->st_sitename;
$page_description = $settings->st_description;
$page_keywords    = $settings->st_keywords;

$og_title       = $page_title;
$og_description = $page_description;
$og_image       = SITE_URL . "/assets/img/logo-vertical.png";
$og_url         = SITE_URL . "/404";

include "views/404.view.php";