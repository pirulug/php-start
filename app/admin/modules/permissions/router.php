<?php

Router::route('permissions')
  ->action(admin_action("permissions.list"))
  ->view(admin_view("permissions.list"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('permissions.list')
  ->register();

Router::route('permission/new')
  ->action(admin_action("permissions.new"))
  ->view(admin_view("permissions.new"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('permissions.new')
  ->register();

Router::route('permission/edit/{id}')
  ->action(admin_action("permissions.edit"))
  ->view(admin_view("permissions.edit"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('permissions.edit')
  ->register();

Router::route('permission/delete/{id}')
  ->action(admin_action("permissions.delete"))
  ->middleware('auth_admin')
  ->permission('permissions.delete')
  ->register();