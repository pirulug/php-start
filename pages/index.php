<?php

require_once "core.php";

$stats   = $visitCounter->get_basic_stats();

$page_title       = $settings->st_sitename;
$page_description = $settings->st_description;
$page_keywords    = $settings->st_keywords;

$og_title       = $page_title;
$og_description = $page_description;
$og_image       = SITE_URL . "/assets/img/logo-vertical.png";
$og_url         = SITE_URL . "/";

include "views/index.view.php";