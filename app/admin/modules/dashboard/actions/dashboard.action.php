<?php

// 1. Total Usuarios (Excluyendo admin ID 1 si lo deseas, o eliminados)
$sql_users  = "SELECT COUNT(*) as total FROM users WHERE user_deleted IS NULL";
$count_user = $connect->query($sql_users)->fetch(PDO::FETCH_OBJ)->total;

// 2. Usuarios Online (Tabla visitor_useronline)
$sql_online   = "SELECT COUNT(*) as total FROM visitor_useronlines";
$count_online = $connect->query($sql_online)->fetch(PDO::FETCH_OBJ)->total;

// 3. Total Vistas de Páginas (Tabla visitor_pages)
$sql_views   = "SELECT SUM(visitor_page_total_views) as total FROM visitor_pages";
$total_views = $connect->query($sql_views)->fetch(PDO::FETCH_OBJ)->total ?? 0;

// 4. Visitantes Únicos (Tabla visitors)
$sql_visitors   = "SELECT COUNT(*) as total FROM visitors";
$total_visitors = $connect->query($sql_visitors)->fetch(PDO::FETCH_OBJ)->total;

// 5. Top 5 Páginas más visitadas
$sql_top_pages = "SELECT visitor_page_title, visitor_page_uri, visitor_page_total_views, visitor_page_type 
                  FROM visitor_pages 
                  ORDER BY visitor_page_total_views DESC LIMIT 5";
$top_pages     = $connect->query($sql_top_pages)->fetchAll(PDO::FETCH_OBJ);

// // 6. Navegadores (Para gráfico de barras simple)
$sql_browsers = "SELECT visitor_browser, COUNT(*) as total 
                 FROM visitors 
                 GROUP BY visitor_browser 
                 ORDER BY total DESC LIMIT 5";
$browsers     = $connect->query($sql_browsers)->fetchAll(PDO::FETCH_OBJ);

// 7. Usuarios Recientes
$sql_recent_users = "SELECT user_login, user_email, user_image, user_created, role_name 
                     FROM users 
                     LEFT JOIN roles ON users.role_id = roles.role_id
                     ORDER BY user_created DESC LIMIT 5";
$recent_users     = $connect->query($sql_recent_users)->fetchAll(PDO::FETCH_OBJ);
