<?php

Sidebar::group('Permissions', 'key', function ($group) {

  $group->item('Nuevo Permiso', admin_route('permission/new'))
    ->can('permissions.new');

  $group->item('Lista de Permisos', admin_route('permissions'))
    ->can('permissions.list');

});