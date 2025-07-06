<?php

require_once "core.php";

// $stats = $visitCounter->get_basic_stats();

$page_title       = SITE_NAME;
$page_description = SITE_DESCRIPTION;
$page_keywords    = SITE_KEYWORDS;

$og_title       = $page_title;
$og_description = $page_description;
$og_image       = SITE_URL . "/uploads/site/" . $st_og_image;
$og_url         = SITE_URL . "/";

include "views/index.view.php";