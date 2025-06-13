<?php

foreach (glob(BASE_DIR . "/core/*.php") as $file) {
  require_once BASE_DIR . "/core/" . $file;
}