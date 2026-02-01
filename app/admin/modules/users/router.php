<?php

Router::route('users')
  ->action(admin_action("users.list"))
  ->view(admin_view("users.list"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('users.list')
  ->register();

Router::route('user/new')
  ->action(admin_action("users.new"))
  ->view(admin_view("users.new"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('users.new')
  ->register();

Router::route('user/edit/{id}')
  ->action(admin_action("users.edit"))
  ->view(admin_view("users.edit"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('users.edit')
  ->register();

Router::route('user/delete/{id}')
  ->action(admin_action("users.delete"))
  ->middleware('auth_admin')
  ->permission('users.delete')
  ->register();