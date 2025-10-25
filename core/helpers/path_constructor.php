<?php

function path_admin(string $path, string $ext = ".php"): string {
  $path = explode('-', $path);
  return BASE_DIR_ADMIN . "/" . $path[0] . "/controllers/" . $path[1] . $ext;
}

function path_admin_view(string $path, string $ext = ".view.php"): string {
  $path = explode('-', $path);
  return BASE_DIR_ADMIN . "/" . $path[0] . "/views/" . $path[1] . $ext;
}

function path_admin_layout(string $layout, string $ext = ".layout.php"): string {
  return BASE_DIR_ADMIN . "/_layouts/" . $layout . $ext;
}

function path_admin_layout_partial(string $partial, string $ext = ".partial.php"): string {
  return BASE_DIR_ADMIN . "/_layouts/partials/" . $partial . $ext;
}


// 

function path_front(string $path, string $ext = ".php"): string {
  $path = explode('/', $path);
  return BASE_DIR_FRONT . "/" . $path[0] . "/controllers/" . $path[1] . $ext;
}

function path_front_view(string $path, string $ext = ".view.php"): string {
  $path = explode('/', $path);
  return BASE_DIR_FRONT . "/" . $path[0] . "/views/" . $path[1] . $ext;
}

function path_front_layout(string $layout, string $ext = ".layout.php"): string {
  return BASE_DIR_FRONT . "/layouts/" . $layout . $ext;
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
