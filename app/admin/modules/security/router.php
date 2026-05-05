<?php

Router::route('permissions')
  ->action('security@permissions.list')
  ->view('security@permissions.list')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('permissions.list')
  ->register();

Router::route('permission/new')
  ->action('security@permissions.new')
  ->view('security@permissions.new')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('permissions.new')
  ->register();

Router::route('permission/edit/{id}')
  ->action('security@permissions.edit')
  ->view('security@permissions.edit')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('permissions.edit')
  ->register();

Router::route('permission/delete/{id}')
  ->action('security@permissions.delete')
  ->middleware('auth_admin')
  ->permission('permissions.delete')
  ->register();

Router::route('roles')
  ->middleware('auth_admin')
  ->permission('roles.list')
  ->action('security@roles.list')
  ->view('security@roles.list')
  ->layout('main')
  ->register();

Router::route('rol/new')
  ->action('security@roles.new')
  ->view('security@roles.new')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('roles.new')
  ->register();

Router::route('rol/edit/{id}')
  ->action('security@roles.edit')
  ->view('security@roles.edit')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('roles.edit')
  ->register();

Router::route('rol/delete/{id}')
  ->action('security@roles.delete')
  ->middleware('auth_admin')
  ->permission('roles.delete')
  ->register();