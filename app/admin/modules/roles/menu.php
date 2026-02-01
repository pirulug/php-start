<?php

Sidebar::group('Roles', 'lock', function ($group) {

  $group->item('Nuevo Rol', admin_route('rol/new'))
    ->can('roles.new');

  $group->item('Lista de Roles', admin_route('roles'))
    ->can('roles.list');

});