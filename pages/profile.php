<?php

require_once "core.php";

$page_title       = "Profile | " . $settings->st_sitename;
$page_description = $settings->st_description;
$page_keywords    = $settings->st_keywords;

$og_title       = $page_title;
$og_description = $page_description;
$og_image       = SITE_URL . "/uploads/site/" . $settings->st_og_image;
$og_url         = SITE_URL . "/profile";

include "views/profile.view.php";