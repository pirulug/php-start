<?php

require_once "core.php";

if (isset($_SESSION['access_message'])) {
  echo "<p>{$_SESSION['access_message']}</p>";
  unset($_SESSION['access_message']);
}

include "views/404.view.php";