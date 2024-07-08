<?php

require_once "../../core.php";

// Acceso
if (!isset($_SESSION['user_name'])) {
  add_message("no inició session", "danger");
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

$check_access = check_access($connect);

// Admin y superAdmin
if ($check_access['user_role'] != 1 && $check_access['user_role'] != 0) {
  add_message("No eres administrador", "danger");
  header('Location: ' . APP_URL . '/');
  exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit  = 10;
$offset = ($page - 1) * $limit;

$currentUserId = $check_access['user_id'];

// Condiciones adicionales dinámicas
$searchColumns = ['user_name', 'user_email'];

$additionalConditions = [
  [
    'sql'   => 'user_role != 0',
    'param' => null,
    'value' => null,
    'type'  => null,
  ],
  [
    'sql'   => 'user_id != :currentUserId',
    'param' => ':currentUserId',
    'value' => $currentUserId,
    'type'  => PDO::PARAM_INT,
  ]
];

$total_results = getTotalResults('users', $searchColumns, $search, $additionalConditions, $connect);
$total_pages   = ceil($total_results / $limit);

$users = getPaginatedResults('users', $searchColumns, $search, $additionalConditions, $limit, $offset, $connect);

/* ========== Theme config ========= */
$theme_title = "Lista de usuarios";
$theme_path  = "user-list";
// $theme_scripts = ["pages/dashboard.js"];
// $theme_styles = ["pages/dashboard.css"];
include BASE_DIR_ADMIN_VIEW . "/users/list.view.php";
/* ================================= */