<?php

$users = Sidebar::group('Users', 'user');

$users->item('Nuevo Usuario', admin_route('user/new'))
  ->can('user.new');

$users->item('Lista de usuarios', admin_route('users'))
  ->can('user.list');