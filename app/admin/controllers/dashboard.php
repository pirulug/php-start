<?php
require_once "core/init.php";

// Seguridad
$accessControl->check_access([1, 2], SITE_URL_ADMIN . "/controllers/logout.php");

// Datos del dashboard
$count_user = $connect->query("SELECT count(*) as total FROM users WHERE user_id <> 1")
  ->fetch(PDO::FETCH_OBJ)->total;

$stats = 18;

// Renderizar dashboard
$theme->render(
  BASE_DIR_ADMIN . "/views/dashboard.view.php",
  [
    'theme_title' => 'Dashboard',
    'theme_path'  => 'dashboard',
    'count_user'  => $count_user,
    'stats'       => $stats
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);

