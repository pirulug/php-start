<?php

// 1. Total Usuarios
$sql_users  = "SELECT COUNT(*) as total FROM users WHERE user_deleted IS NULL";
$count_user = $connect->query($sql_users)->fetch(PDO::FETCH_OBJ)->total;

// 2. Total Roles
$sql_roles   = "SELECT COUNT(*) as total FROM roles";
$count_roles = $connect->query($sql_roles)->fetch(PDO::FETCH_OBJ)->total;

// 3. Conteo de Módulos Activos (escaneando directorio)
$modules_path = BASE_DIR . '/app/admin/modules';
$modules_count = 0;
if (is_dir($modules_path)) {
    $modules_count = count(array_filter(scandir($modules_path), function($item) use ($modules_path) {
        return is_dir($modules_path . '/' . $item) && !in_array($item, ['.', '..']);
    }));
}

// 4. Usuarios Recientes
$sql_recent_users = "SELECT user_login, user_email, user_image, user_created, role_name 
                     FROM users 
                     LEFT JOIN roles ON users.role_id = roles.role_id
                     ORDER BY user_created DESC LIMIT 6";
$recent_users     = $connect->query($sql_recent_users)->fetchAll(PDO::FETCH_OBJ);

// 5. Información del Sistema
$system_info = [
  'php_version' => PHP_VERSION,
  'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
  'os' => PHP_OS_FAMILY,
  'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
  'memory_limit' => ini_get('memory_limit'),
  'post_max' => ini_get('post_max_size'),
  'upload_max' => ini_get('upload_max_filesize'),
];

// 6. Espacio en Disco (Manejo de restricciones open_basedir)
try {
  $path_to_check = DIRECTORY_SEPARATOR === '\\' ? "C:" : "/";
  // Si hay restricciones de open_basedir, usamos el directorio base de la app
  $disk_total = @disk_total_space(BASE_DIR) ?: 0;
  $disk_free = @disk_free_space(BASE_DIR) ?: 0;
  
  if ($disk_total > 0) {
    $disk_used = $disk_total - $disk_free;
    $disk_percentage = round(($disk_used / $disk_total) * 100);
  } else {
    $disk_percentage = 0;
  }
} catch (Exception $e) {
  $disk_percentage = 0;
}
