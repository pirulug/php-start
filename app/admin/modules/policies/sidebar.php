<?php

Sidebar::group('Políticas', 'file-text', function ($group) {

  $group->item('Nueva Política', admin_route('policy/new'))
    ->can('policies.new');

  $group->item('Lista de Políticas', admin_route('policies'))
    ->can('policies.list');

});