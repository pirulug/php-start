<?php

Sidebar::group('Componentes', 'package', function ($group) {

  $group->item('Datatables', admin_route('componentes/datatable'))
    ->can('roles.new');

  $group->item('Captcha', admin_route('componentes/captcha'))
    ->can('roles.new');

  $group->item('SweetAlert2', admin_route('componentes/sweetalert'))
    ->can('roles.new');

});