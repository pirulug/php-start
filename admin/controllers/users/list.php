<?php

require_once "../../core.php";

if (!isUserLoggedIn()) {
  header('Location: ' . APP_URL . '/admin/controllers/login.php');
  exit();
}

if (!$accessControl->hasAccess([0, 1], $_SESSION['user_role'])) {
  header("Location: " . APP_URL . "/admin/controllers/dashboard.php");
  exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit  = 10;
$offset = ($page - 1) * $limit;

$currentUserId = $_SESSION['user_id'];

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