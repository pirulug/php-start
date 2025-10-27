<?php

// $paginator = new Paginator($connect, 'users', 10);
// $paginator->setSearchColumns(['user_name', 'user_email']);
// $paginator->setOrder('user_id', 'DESC');
// $paginator->setAdditionalConditions([
//   // [
//   //   'sql'   => 'role_id != 1',
//   //   'param' => null,
//   //   'value' => null
//   // ],
//   [
//     'sql'   => 'user_id != :currentUserId',
//     'param' => ':currentUserId',
//     'value' => $_SESSION['user_id']
//   ]
// ]);

// $users = $paginator->getResults();

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

// print_r($users);
// exit();