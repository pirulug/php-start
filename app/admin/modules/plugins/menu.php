<?php

Sidebar::group('Plugins', 'lock', function ($group) {

  $group->item('Datatables', admin_route('plugins/datatable'))
    ->can('roles.new');

  $group->item('Captcha', admin_route('plugins/captcha'))
    ->can('roles.new');

});