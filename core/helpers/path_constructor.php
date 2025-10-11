<?php

function path_admin(string $path, string $ext = ".php"): string {
  return BASE_DIR_ADMIN . "/controllers/" . $path . $ext;
}

function path_front(string $path, string $ext = ".php"): string {
  return BASE_DIR_FRONT . "/controllers/" . $path . $ext;
}

function path_api(string $path, string $ext = ".php"): string {
  return BASE_DIR_API . "/" . $path . $ext;
}

function path_ajax(string $path, string $ext = ".php"): string {
  return BASE_DIR_AJAX . "/" . $path . $ext;
}

// Para construir url 
function url_admin(string $path) {
  return SITE_URL_ADMIN . "/" . $path;
}

function url_front(string $path) {
  return SITE_URL_FRONT . "/" . $path;
}

function url_api(string $path) {
  return SITE_URL_API . "/" . $path;
}

function url_ajax(string $path) {
  return SITE_URL_AJAX . "/" . $path;
}
