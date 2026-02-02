<?php

$GLOBALS['__view_blocks']   = [];
$GLOBALS['__current_block'] = null;

/* --------------------------------------------------
 * START BLOCK
 * -------------------------------------------------- */
function start_block($name) {
  $GLOBALS['__current_block'] = $name;
  ob_start();
}

/* --------------------------------------------------
 * END BLOCK
 * -------------------------------------------------- */
function end_block() {
  $name = $GLOBALS['__current_block'];

  if ($name === null) {
    return;
  }

  $GLOBALS['__view_blocks'][$name] = ob_get_clean();
  $GLOBALS['__current_block']      = null;
}

/* --------------------------------------------------
 * GET BLOCK
 * -------------------------------------------------- */
function get_block($name, $default = '') {
  return $GLOBALS['__view_blocks'][$name] ?? $default;
}

function has_block($name) {
  return isset($GLOBALS['__view_blocks'][$name])
    && trim($GLOBALS['__view_blocks'][$name]) !== '';
}
