<?php

// function load_admin_page(string $path, string $title, string $file, string $ext = ".php") {
//   global $accessManager;

//   $theme_title = $title;
//   $theme_path  = $path;

//   $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));

//   include BASE_DIR_ADMIN . "/controllers/" . $file . $ext;
// }

/**
 * Carga una página del panel admin y verifica permisos de acceso.
 *
 * @param string $path  Identificador de acceso (para AccessManager)
 * @param string $title Título mostrado en la vista
 * @param string $file  Archivo a incluir (ruta relativa desde /admin/)
 * @param string $ext   Extensión del archivo, por defecto ".php"
 */
function load_admin_page(string $path, string $title, string $file, string $ext = ".php"): void {
  global $accessManager, $connect, $theme;

  // Definir variables globales para la vista
  $theme_title = $title;
  $theme_path  = $path;

  // Verificación de acceso usando AccessManager
  $accessManager->ensure_access($theme_path, $theme_title, url_admin("logout"));

  // Ruta completa del archivo a incluir
  $full_path = BASE_DIR_ADMIN . "/controllers/" . ltrim($file, "/") . $ext;

  // Verificar existencia del archivo antes de incluir
  if (file_exists($full_path)) {
    include $full_path;
  } else {
    die("<b>Error:</b> No se encontró el archivo <code>{$full_path}</code>.");
  }
}


function path_admin(string $path, string $ext = ".php"): string {
  return BASE_DIR_ADMIN . "/controllers/" . $path . $ext;
}



// function load_admin_page($path, $title, $file) {
//   global $connect, $accessControl;

//   register_permission_if_not_exists($connect, $path, $title);
//   $accessControl->check_access($path, url_admin("logout"));
//   include path_admin($file);
// }

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
