<?php

require_once "../../core.php";

$accessControl->check_access([1, 2], SITE_URL_ADMIN . "/controllers/404.php");

$paginator = new Paginator($connect, 'users', 10);
$paginator->setSearchColumns(['user_name', 'user_email']);
$paginator->setOrder('user_id', 'DESC');
$paginator->setAdditionalConditions([
  [
    'sql' => 'user_role != 1',
    'param' => null,
    'value' => null
  ],
  [
    'sql' => 'user_id != :currentUserId',
    'param' => ':currentUserId',
    'value' => $_SESSION['user_id']
  ]
]);

$users = $paginator->getResults();

/* ========== Theme config ========= */
$theme_title = "Lista de usuarios";
$theme_path  = "user-list";
include BASE_DIR_ADMIN . "/views/users/list.view.php";
/* ================================= */