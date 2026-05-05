<?php

function static_assets_css($css) {
  return "<link rel=\"stylesheet\" href=\"".APP_URL . "/static/assets/css/{$css}\">\n";
}

function static_assets_js($js) {
  return "<script src=\"".APP_URL . "/static/assets/js/{$js}\"></script>\n";
}

function static_libs_css($dir, $file){
  return "<link rel=\"stylesheet\" href=\"".APP_URL . "/static/libs/{$dir}/{$file}\">\n";
}

function static_libs_js($dir, $file){
  return "<script src=\"".APP_URL . "/static/libs/{$dir}/{$file}\"></script>\n";
}