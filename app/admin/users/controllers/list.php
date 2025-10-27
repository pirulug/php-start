<?php

$dt    = new PaginatorPlus($connect);
$users = $dt->from('users')
  ->join('INNER JOIN roles ON roles.role_id = users.role_id')
  ->columns([
    'users.user_id',
    'users.user_image',
    'users.user_name',
    'users.user_email',
    'roles.role_name',
    'users.user_status',
    'users.user_created'
  ])
  ->searchColumns(['users.user_name', 'users.user_email', 'roles.role_name'])
  ->condition(
    'users.user_id != :currentUserId', 
    ':currentUserId', 
    $_SESSION['user_id']
    )
  ->order('users.user_id', 'DESC')
  ->perPage(10)
  ->get();
