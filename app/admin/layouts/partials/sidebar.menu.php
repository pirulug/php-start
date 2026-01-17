<?php

Sidebar::item('Dashboard', admin_route('dashboard'))
  ->icon('sliders')
  ->can('dashboard.dashboard');

// Sidebar::header('Plugin');

Sidebar::group('Plugins', 'lock', function ($group) {

  $group->item('Datatables', admin_route('plugins/datatable'))
    ->can('roles.new');

});

// Sidebar::header('Configuración');

$users = Sidebar::group('Users', 'user');

$users->item('Nuevo Usuario', admin_route('user/new'))
  ->can('user.new');

$users->item('Lista de usuarios', admin_route('users'))
  ->can('user.list');


Sidebar::group('Roles', 'lock', function ($group) {

  $group->item('Nuevo Rol', admin_route('rol/new'))
    ->can('roles.new');

  $group->item('Lista de Roles', admin_route('roles'))
    ->can('roles.list');

});

Sidebar::group('Permissions', 'key', function ($group) {

  $group->item('Nuevo Permiso', admin_route('permission/new'))
    ->can('permissions.new');

  $group->item('Lista de Permisos', admin_route('permissions'))
    ->can('permissions.list');

});

Sidebar::group('Settings', 'settings', function ($group) {

  $group->item('General', admin_route('settings/general'))
    ->can('settings.new');

  $group->item('Opciones', admin_route('settings/options'))
    ->can('settings.options');

  $group->item('Date & Time', admin_route('settings/date_time'))
    ->can('settings.date_time');

  $group->item('Captcha', admin_route('settings/captcha'))
    ->can('settings.captcha');

  $group->item('SMTP', admin_route('settings/smtp'))
    ->can('settings.smtp');

  $group->item('Brand', admin_route('settings/brand'))
    ->can('settings.brand');

  $group->item('Información', admin_route('settings/info'))
    ->can('settings.info');

  $group->item('robots.txt', admin_route('settings/robots'))
    ->can('settings.robots');

  $group->item('Sitemap.xml', admin_route('settings/sitemap'))
    ->can('settings.sitemap');

  $group->item('Backups', admin_route('settings/backups'))
    ->can('settings.backups');

  $group->item('Visitors', admin_route('settings/visitors'))
    ->can('settings.visitors');

});
