<?php

Sidebar::group('Seguridad', 'shield', function ($group) {

  // Roles
  $group->item('Nuevo Rol', admin_route('rol/new'))
    ->can('roles.new');

  $group->item('Lista de Roles', admin_route('roles'))
    ->can('roles.list');

  // Permisos
  $group->item('Nuevo Permiso', admin_route('permission/new'))
    ->can('permissions.new');

  $group->item('Lista de Permisos', admin_route('permissions'))
    ->can('permissions.list');

});
