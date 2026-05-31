<?php

// -----------------------------------------------------------------------------
// SECCIÓN: MÓDULOS DEL SISTEMA PROTEGIDOS
// -----------------------------------------------------------------------------
$protected_modules = ["index", "auth", "errors"];

// -----------------------------------------------------------------------------
// SECCIÓN: OBTENCIÓN DE MÓDULOS FÍSICOS Y ESTADO DE BD
// -----------------------------------------------------------------------------
$dir_front = BASE_DIR . "/app/front/modules";

$modules_front_physical = [];
if (is_dir($dir_front)) {
  foreach (scandir($dir_front) as $file) {
    if ($file !== "." && $file !== ".." && is_dir("{$dir_front}/{$file}")) {
      $modules_front_physical[] = $file;
    }
  }
}

$modules_config = site_config()->get("modules");

// -----------------------------------------------------------------------------
// SECCIÓN: PROCESAMIENTO DEL FORMULARIO (POST)
// -----------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  global $connect;
  global $config;
  global $notifier;

  $post_front_active = $_POST["front_active"] ?? [];
  $front_config_current = ($modules_config && isset($modules_config->front)) ? (array)$modules_config->front : [];
  
  $new_front_data = [];
  foreach ($modules_front_physical as $mod) {
    $active = in_array($mod, $protected_modules) || isset($post_front_active[$mod]);
    $order = isset($front_config_current[$mod]->order) ? (int)$front_config_current[$mod]->order : 999;
    $new_front_data[$mod] = [
      "active" => $active,
      "order"  => $order
    ];
  }

  // Combinar con los datos existentes de admin y api
  $current_admin = ($modules_config && isset($modules_config->admin)) ? (array)$modules_config->admin : [];
  $current_api   = ($modules_config && isset($modules_config->api)) ? (array)$modules_config->api : [];

  $final_modules_data = [
    "admin" => $current_admin,
    "front" => $new_front_data,
    "api"   => $current_api
  ];

  $json_value = json_encode($final_modules_data);

  $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = 'modules'");
  $stmt->bindParam(":value", $json_value);
  $stmt->execute();

  $config->refresh();

  $cache_files = [
    BASE_DIR . "/storage/caches/site_options.cache.php",
    BASE_DIR . "/storage/caches/routes.home.php"
  ];
  foreach ($cache_files as $file) {
    if (file_exists($file)) {
      unlink($file);
    }
  }

  $notifier->success("Modulos de Frontend actualizados correctamente.")->add();
  header("Location: " . admin_route("modules/front"));
  exit();
}

// -----------------------------------------------------------------------------
// SECCIÓN: PREPARACIÓN DE DATOS PARA LA VISTA
// -----------------------------------------------------------------------------
$front_config_current = ($modules_config && isset($modules_config->front)) ? (array)$modules_config->front : [];
$view_front_modules = [];

foreach ($modules_front_physical as $mod) {
  $is_protected = in_array($mod, $protected_modules);
  $active = $is_protected || (isset($front_config_current[$mod]->active) ? (bool)$front_config_current[$mod]->active : false);
  $view_front_modules[$mod] = [
    "active"    => $active,
    "protected" => $is_protected
  ];
}

ksort($view_front_modules);
