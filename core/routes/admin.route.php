<?php

Router::prefix(PATH_ADMIN, CTX_ADMIN, function () {

  Router::get('login')
    ->action(admin_action("auth.login"))
    ->view(admin_view("auth.login"))
    ->layout(admin_layout("auth"));

  Router::get('logout')
    ->action(admin_action("auth.logout"));

  Router::get('dashboard')
    ->action(admin_action("dashboard.dashboard"))
    ->view(admin_view("dashboard.dashboard"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'dashboard.dashboard');

  // Account
  Router::get('account/profile')
    ->action(admin_action("account.profile"))
    ->view(admin_view("account.profile"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'account.profile');

  Router::get('account/settings')
    ->action(admin_action("account.settings"))
    ->view(admin_view("account.settings"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'account.settings');

  // Users
  Router::get('users')
    ->action(admin_action("users.list"))
    ->view(admin_view("users.list"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'users.list');

  Router::get('user/new')
    ->action(admin_action("users.new"))
    ->view(admin_view("users.new"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'users.new');

  Router::get('user/edit/{id}')
    ->action(admin_action("users.edit"))
    ->view(admin_view("users.edit"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'users.edit');

  Router::get('user/delete/{id}')
    ->action(admin_action("users.delete"))
    ->middleware('auth_admin')
    ->middleware('permission', 'users.delete');

  // Roles
  Router::get('roles')
    ->middleware('auth_admin')
    ->middleware('permission', 'roles.list')
    ->action(admin_action("roles.list"))
    ->view(admin_view("roles.list"))
    ->layout(admin_layout());

  Router::get('rol/new')
    ->action(admin_action("roles.new"))
    ->view(admin_view("roles.new"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'roles.new');

  Router::get('rol/edit/{id}')
    ->action(admin_action("roles.edit"))
    ->view(admin_view("roles.edit"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'roles.edit');

  Router::get('rol/delete/{id}')
    ->action(admin_action("roles.delete"))
    ->middleware('auth_admin')
    ->middleware('permission', 'roles.delete');

  // Permisions
  Router::get('permissions')
    ->action(admin_action("permissions.list"))
    ->view(admin_view("permissions.list"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'permissions.list');

  Router::get('permission/new')
    ->action(admin_action("permissions.new"))
    ->view(admin_view("permissions.new"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'permissions.new');

  Router::get('permission/edit/{id}')
    ->action(admin_action("permissions.edit"))
    ->view(admin_view("permissions.edit"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'permissions.edit');

  Router::get('permission/delete/{id}')
    ->action(admin_action("permissions.delete"))
    ->middleware('auth_admin')
    ->middleware('permission', 'permissions.delete');

  // Settings
  Router::get('settings/general')
    ->action(admin_action("settings.general"))
    ->view(admin_view("settings.general"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.general');

  Router::get('settings/backups')
    ->action(admin_action("settings.backups"))
    ->view(admin_view("settings.backups"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.backups');

  Router::get('settings/brand')
    ->action(admin_action("settings.brand"))
    ->view(admin_view("settings.brand"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.brand');

  Router::get('settings/captcha')
    ->action(admin_action("settings.captcha"))
    ->view(admin_view("settings.captcha"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.captcha');

  Router::get('settings/date_time')
    ->action(admin_action("settings.date_time"))
    ->view(admin_view("settings.date_time"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.date_time');

  Router::get('settings/info')
    ->action(admin_action("settings.info"))
    ->view(admin_view("settings.info"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.info');

  Router::get('settings/robots')
    ->action(admin_action("settings.robots"))
    ->view(admin_view("settings.robots"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.robots');

  Router::get('settings/sitemap')
    ->action(admin_action("settings.sitemap"))
    ->view(admin_view("settings.sitemap"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.sitemap');

  Router::get('settings/smtp')
    ->action(admin_action("settings.smtp"))
    ->view(admin_view("settings.smtp"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.smtp');

  Router::get('settings/social')
    ->action(admin_action("settings.social"))
    ->view(admin_view("settings.social"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.social');

  Router::get('settings/visitors')
    ->action(admin_action("settings.visitors"))
    ->view(admin_view("settings.visitors"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->middleware('permission', 'settings.visitors');
});

