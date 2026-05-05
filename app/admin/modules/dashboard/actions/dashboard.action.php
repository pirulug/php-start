<?php

// 1. Total Usuarios
$sql_users  = "SELECT COUNT(*) as total FROM users WHERE user_deleted IS NULL";
$count_user = $connect->query($sql_users)->fetch(PDO::FETCH_OBJ)->total;

// 2. Total Roles
$sql_roles   = "SELECT COUNT(*) as total FROM roles";
$count_roles = $connect->query($sql_roles)->fetch(PDO::FETCH_OBJ)->total;


// 4. Total Contenidos (Posts, Pages, Policies)
$sql_posts   = "SELECT COUNT(*) as total FROM posts WHERE post_status = 1";
$count_posts = $connect->query($sql_posts)->fetch(PDO::FETCH_OBJ)->total;

// 5. Usuarios Recientes (JOIN Metadata)
$sql_recent_users = "SELECT u.user_login, u.user_email, u.user_image, u.user_created, r.role_name 
                     FROM users u
                     LEFT JOIN usermeta um ON um.user_id = u.user_id AND um.usermeta_key = 'role_id'
                     LEFT JOIN roles r ON um.usermeta_value = r.role_id
                     ORDER BY u.user_created DESC LIMIT 6";
$recent_users     = $connect->query($sql_recent_users)->fetchAll(PDO::FETCH_OBJ);

// 6. Información del Sistema
$system_info = [
  'php_version'     => PHP_VERSION,
  'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
  'os'              => PHP_OS_FAMILY,
  'memory_usage'    => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
  'memory_limit'    => ini_get('memory_limit'),
  'post_max'        => ini_get('post_max_size'),
  'upload_max'      => ini_get('upload_max_filesize'),
];

// 7. Espacio en Disco
try {
  $disk_total = @disk_total_space(BASE_DIR) ?: 0;
  $disk_free  = @disk_free_space(BASE_DIR) ?: 0;

  if ($disk_total > 0) {
    $disk_used       = $disk_total - $disk_free;
    $disk_percentage = round(($disk_used / $disk_total) * 100);
  } else {
    $disk_percentage = 0;
  }
} catch (Exception $e) {
  $disk_percentage = 0;
}