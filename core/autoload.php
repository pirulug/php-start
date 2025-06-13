<?php
foreach (glob(BASE_DIR . '/config/*.php') as $file) {
  require_once $file;
}

foreach (glob(BASE_DIR . '/libs/*.php') as $file) {
  require_once $file;
}

foreach (glob(BASE_DIR . '/helpers/*.php') as $file) {
  require_once $file;
}
