<?php

require_once "core.php";

if (isset($_SESSION['access_message'])) {
  echo "<p>{$_SESSION['access_message']}</p>";
  unset($_SESSION['access_message']);
}

$page_title       = "404 | " . SITE_NAME;
$page_description = SITE_DESCRIPTION;
$page_keywords    = SITE_KEYWORDS;

$og_title       = $page_title;
$og_description = $page_description;
$og_image       = SITE_URL . "/uploads/site/" . $st_og_image;
$og_url         = SITE_URL . "/signin";

include "views/404.view.php";