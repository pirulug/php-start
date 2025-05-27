<?php 

$antiXss = new AntiXSS();

function clear_data($data) {
  static $antiXss = null;
  if ($antiXss === null) {
    $antiXss = new AntiXSS();
  }
  return $antiXss->clean($data);
}