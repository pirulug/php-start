<?php

// -----------------------------------------------------------------------------
// SECCIÓN: MÓDULOS DEL SISTEMA PROTEGIDOS
// -----------------------------------------------------------------------------
$protected_modules = ["auth", "dashboard", "security", "users", "settings", "modules"];

// -----------------------------------------------------------------------------
// SECCIÓN: OBTENCIÓN DE MÓDULOS FÍSICOS Y ESTADO DE BD
// -----------------------------------------------------------------------------
$dir_admin = BASE_DIR . "/app/admin/modules";

$modules_admin_physical = [];
if (is_dir($dir_admin)) {
  foreach (scandir($dir_admin) as $file) {
    if ($file !== "." && $file !== ".." && is_dir("{$dir_admin}/{$file}")) {
      $modules_admin_physical[] = $file;
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

  $post_admin_active  = $_POST["admin_active"] ?? [];
  $post_admin_sidebar = $_POST["admin_sidebar"] ?? [];
  $post_admin_order   = $_POST["admin_order"] ?? [];

  $admin_with_sidebar_temp = [];
  $admin_no_sidebar_temp = [];

  foreach ($modules_admin_physical as $mod) {
    $active = in_array($mod, $protected_modules) || isset($post_admin_active[$mod]);
    $has_sidebar = file_exists(BASE_DIR . "/app/admin/modules/{$mod}/sidebar.php");
    $sidebar = $has_sidebar && isset($post_admin_sidebar[$mod]);

    if ($has_sidebar && $sidebar) {
      $order = isset($post_admin_order[$mod]) ? (int)$post_admin_order[$mod] : 999;
      $admin_with_sidebar_temp[$mod] = [
        "active"  => $active,
        "sidebar" => $sidebar,
        "order"   => $order
      ];
    } else {
      $admin_no_sidebar_temp[$mod] = [
        "active"  => $active,
        "sidebar" => $sidebar,
        "order"   => 999
      ];
    }
  }

  // Ordenar según lo establecido por el usuario
  uasort($admin_with_sidebar_temp, function ($a, $b) {
    return $a["order"] <=> $b["order"];
  });

  $new_admin_data = [];
  $idx = 1;
  foreach ($admin_with_sidebar_temp as $mod => $data) {
    $new_admin_data[$mod] = [
      "active"  => $data["active"],
      "sidebar" => $data["sidebar"],
      "order"   => $idx++
    ];
  }

  foreach ($admin_no_sidebar_temp as $mod => $data) {
    $new_admin_data[$mod] = [
      "active"  => $data["active"],
      "sidebar" => $data["sidebar"],
      "order"   => $idx++
    ];
  }

  // Mezclar para no pisar el front ni la api
  $current_front = ($modules_config && isset($modules_config->front)) ? (array)$modules_config->front : [];
  $current_api   = ($modules_config && isset($modules_config->api)) ? (array)$modules_config->api : [];

  $final_modules_data = [
    "admin" => $new_admin_data,
    "front" => $current_front,
    "api"   => $current_api
  ];

  $json_value = json_encode($final_modules_data);

  $stmt = $connect->prepare("UPDATE options SET option_value = :value WHERE option_key = 'modules'");
  $stmt->bindParam(":value", $json_value);
  $stmt->execute();

  $config->refresh();

  $cache_files = [
    BASE_DIR . "/storage/caches/site_options.cache.php",
    BASE_DIR . "/storage/caches/routes.admin.php",
    BASE_DIR . "/storage/caches/sidebar.admin.php"
  ];
  foreach ($cache_files as $file) {
    if (file_exists($file)) {
      unlink($file);
    }
  }

  $notifier->success("Modulos de Administracion actualizados correctamente.")->add();
  header("Location: " . admin_route("modules"));
  exit();
}

// -----------------------------------------------------------------------------
// SECCIÓN: PREPARACIÓN DE DATOS PARA LA VISTA
// -----------------------------------------------------------------------------
$admin_config_current = ($modules_config && isset($modules_config->admin)) ? (array)$modules_config->admin : [];
$view_admin_with_sidebar = [];
$view_admin_no_sidebar = [];

foreach ($modules_admin_physical as $mod) {
  $is_protected = in_array($mod, $protected_modules);
  $active = $is_protected || (isset($admin_config_current[$mod]->active) ? (bool)$admin_config_current[$mod]->active : false);
  $order  = isset($admin_config_current[$mod]->order) ? (int)$admin_config_current[$mod]->order : 999;
  
  $has_sidebar = file_exists(BASE_DIR . "/app/admin/modules/{$mod}/sidebar.php");
  $sidebar = isset($admin_config_current[$mod]->sidebar) ? (bool)$admin_config_current[$mod]->sidebar : $has_sidebar;

  $mod_info = [
    "active"      => $active,
    "sidebar"     => $sidebar,
    "order"       => $order,
    "protected"   => $is_protected,
    "has_sidebar" => $has_sidebar
  ];
  
  if ($has_sidebar && $sidebar) {
    $view_admin_with_sidebar[$mod] = $mod_info;
  } else {
    $view_admin_no_sidebar[$mod] = $mod_info;
  }
}

uasort($view_admin_with_sidebar, function ($a, $b) {
  return $a["order"] <=> $b["order"];
});

ksort($view_admin_no_sidebar);
