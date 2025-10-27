<?php

$dt    = new PaginatorPlus($connect);
$roles = $dt->from('roles')
  ->columns([
    'role_id',
    'role_name',
    'role_description'
  ])
  ->searchColumns([
    'role_name',
    'role_description'
  ])
  ->order('roles.role_id', 'DESC')
  ->perPage(10)
  ->get();
