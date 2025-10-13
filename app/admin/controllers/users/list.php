<?php

require_once "core/init.php";

$paginator = new Paginator($connect, 'users', 10);
$paginator->setSearchColumns(['user_name', 'user_email']);
$paginator->setOrder('user_id', 'DESC');
$paginator->setAdditionalConditions([
  [
    'sql'   => 'role_id != 1',
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

// Renderizar template
$theme->render(
  BASE_DIR_ADMIN . "/views/users/list.view.php",
  [
    'theme_title' => $theme_title,
    'theme_path'  => $theme_path,
    "users"       => $users,
    "paginator"   => $paginator
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);