<?php

require_once "core.php";

$accessControl->check_access([1, 2, 3], SITE_URL);

$pageTitle = "Home";
include "views/profile.view.php";