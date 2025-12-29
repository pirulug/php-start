<?php

$dt    = new PaginatorPlus($connect);
$roles = $dt
  ->from('roles')
  ->select([
    'role_id',
    'role_name',
    'role_description'
  ])
  ->search([
    'role_name',
    'role_description'
  ])
  ->orderBy('roles.role_id', 'DESC')
  ->perPage(10)
  ->get();