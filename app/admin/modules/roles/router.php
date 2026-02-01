<?php

Router::route('roles')
  ->middleware('auth_admin')
  ->permission('roles.list')
  ->action(admin_action("roles.list"))
  ->view(admin_view("roles.list"))
  ->layout(admin_layout())
  ->register();

Router::route('rol/new')
  ->action(admin_action("roles.new"))
  ->view(admin_view("roles.new"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('roles.new')
  ->register();

Router::route('rol/edit/{id}')
  ->action(admin_action("roles.edit"))
  ->view(admin_view("roles.edit"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('roles.edit')
  ->register();

Router::route('rol/delete/{id}')
  ->action(admin_action("roles.delete"))
  ->middleware('auth_admin')
  ->permission('roles.delete')
  ->register();