<?php

// -----------------------------------------------------------------------------
// SECCIÓN: MÓDULOS DEL SISTEMA PROTEGIDOS
// -----------------------------------------------------------------------------
$protected_modules = ["users"];

// -----------------------------------------------------------------------------
// SECCIÓN: OBTENCIÓN DE MÓDULOS FÍSICOS Y ESTADO DE BD
// -----------------------------------------------------------------------------
$dir_api = BASE_DIR . "/app/api";

$modules_api_physical = [];
if (is_dir($dir_api)) {
  foreach (scandir($dir_api) as $file) {
    if ($file !== "." && $file !== ".." && is_dir("{$dir_api}/{$file}")) {
      $modules_api_physical[] = $file;
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

  $post_api_active = $_POST["api_active"] ?? [];
  $api_config_current = ($modules_config && isset($modules_config->api)) ? (array)$modules_config->api : [];
  
  $new_api_data = [];
  foreach ($modules_api_physical as $mod) {
    $active = in_array($mod, $protected_modules) || isset($post_api_active[$mod]);
    $order = isset($api_config_current[$mod]->order) ? (int)$api_config_current[$mod]->order : 999;
    $new_api_data[$mod] = [
      "active" => $active,
      "order"  => $order
    ];
  }

  // Combinar con los datos existentes de admin y front
  $current_admin = ($modules_config && isset($modules_config->admin)) ? (array)$modules_config->admin : [];
  $current_front = ($modules_config && isset($modules_config->front)) ? (array)$modules_config->front : [];

  $final_modules_data = [
    "admin" => $current_admin,
    "front" => $current_front,
    "api"   => $new_api_data
  ];

  $json_value = json_encode($final_modules_data);

  $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = 'modules'");
  $stmt->bindParam(":value", $json_value);
  $stmt->execute();

  $config->refresh();

  $cache_files = [
    BASE_DIR . "/storage/caches/site_options.cache.php",
    BASE_DIR . "/storage/caches/routes.api.php"
  ];
  foreach ($cache_files as $file) {
    if (file_exists($file)) {
      unlink($file);
    }
  }

  $notifier->success("Modulos de API actualizados correctamente.")->add();
  header("Location: " . admin_route("modules/api"));
  exit();
}

// -----------------------------------------------------------------------------
// SECCIÓN: PREPARACIÓN DE DATOS PARA LA VISTA
// -----------------------------------------------------------------------------
$api_config_current = ($modules_config && isset($modules_config->api)) ? (array)$modules_config->api : [];
$view_api_modules = [];

foreach ($modules_api_physical as $mod) {
  $is_protected = in_array($mod, $protected_modules);
  $active = $is_protected || (isset($api_config_current[$mod]->active) ? (bool)$api_config_current[$mod]->active : false);
  $view_api_modules[$mod] = [
    "active"    => $active,
    "protected" => $is_protected
  ];
}

ksort($view_api_modules);
