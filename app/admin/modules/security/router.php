<?php

Router::route('permissions')
  ->action(admin_action("security.permissions.list"))
  ->view(admin_view("security.permissions.list"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('permissions.list')
  ->register();

Router::route('permission/new')
  ->action(admin_action("security.permissions.new"))
  ->view(admin_view("security.permissions.new"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('permissions.new')
  ->register();

Router::route('permission/edit/{id}')
  ->action(admin_action("security.permissions.edit"))
  ->view(admin_view("security.permissions.edit"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('permissions.edit')
  ->register();

Router::route('permission/delete/{id}')
  ->action(admin_action("security.permissions.delete"))
  ->middleware('auth_admin')
  ->permission('permissions.delete')
  ->register();

Router::route('roles')
  ->middleware('auth_admin')
  ->permission('roles.list')
  ->action(admin_action("security.roles.list"))
  ->view(admin_view("security.roles.list"))
  ->layout(admin_layout())
  ->register();

Router::route('rol/new')
  ->action(admin_action("security.roles.new"))
  ->view(admin_view("security.roles.new"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('roles.new')
  ->register();

Router::route('rol/edit/{id}')
  ->action(admin_action("security.roles.edit"))
  ->view(admin_view("security.roles.edit"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('roles.edit')
  ->register();

Router::route('rol/delete/{id}')
  ->action(admin_action("security.roles.delete"))
  ->middleware('auth_admin')
  ->permission('roles.delete')
  ->register();
