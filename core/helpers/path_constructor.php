<?php

function path_admin(string $path, string $ext = ".php"): string {
  return BASE_DIR_ADMIN . "/controllers/" . $path . $ext;
}

function path_front(string $path, string $ext = ".php"): string {
  return BASE_DIR_PAGES . "/controllers/" . $path . $ext;
}

function path_api(string $path, string $ext = ".php"): string {
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
