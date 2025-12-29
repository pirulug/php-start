<?php

function clear_data(string $data): string {
  static $antiXss = null;

  if ($antiXss === null) {
    $antiXss = new AntiXSS();
  }

  return $antiXss->clean($data);
}
