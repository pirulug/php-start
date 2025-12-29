<?php

$dt    = new PaginatorPlus($connect);
$users = $dt
  ->from('users')
  ->select([
    'users.user_id',
    'users.user_image',
    'users.user_login',
    'users.user_email',
    'roles.role_name',
    'users.user_status',
    'users.user_created'
  ])
  ->join('roles', 'roles.role_id', '=', 'users.role_id')
  ->where('users.user_id', '!=', $_SESSION['user_id'])
  ->search(['users.user_login', 'users.user_email', 'roles.role_name'])
  ->orderBy('users.user_id', 'DESC')
  ->perPage(10)
  ->get();

// echo $dt->renderLinks('?');