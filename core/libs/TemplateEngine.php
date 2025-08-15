<?php

class TemplateEngine {

  private $blocks = [];

  function blockStart($name) {
    global $blocks;
    ob_start();
  }

  function blockEnd($name) {
    global $blocks;
    $blocks[$name] = ob_get_clean();
  }

  function block($name) {
    global $blocks;
    if (isset($blocks[$name])) {
      echo $blocks[$name];
    }
  }
}