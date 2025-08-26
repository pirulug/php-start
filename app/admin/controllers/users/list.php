<?php

require_once "core/init.php";

$paginator = new Paginator($connect, 'users', 10);
$paginator->setSearchColumns(['user_name', 'user_email']);
$paginator->setOrder('user_id', 'DESC');
$paginator->setAdditionalConditions([
  [
    'sql'   => 'user_role != 1',
    'param' => null,
    'value' => null
  ],
  [
    'sql'   => 'user_id != :currentUserId',
    'param' => ':currentUserId',
    'value' => $_SESSION['user_id']
  ]
]);

$users = $paginator->getResults();

// Renderizar dashboard
$theme->render(
  BASE_DIR_ADMIN . "/views/users/list.view.php",
  [
    'theme_title' => 'Lista de usuarios',
    'theme_path'  => 'user-list',
    "users"       => $users,
    "paginator"   => $paginator
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);