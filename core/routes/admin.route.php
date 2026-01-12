<?php

Router::prefix(PATH_ADMIN, CTX_ADMIN, function () {

  Router::route('')
    ->action(admin_action("auth.login"))
    ->view(admin_view("auth.login"))
    ->layout(admin_layout("auth"))
    ->register();

  Router::route('login')
    ->action(admin_action("auth.login"))
    ->view(admin_view("auth.login"))
    ->layout(admin_layout("auth"))
    ->register();

  Router::route('logout')
    ->action(admin_action("auth.logout"))
    ->register();

  Router::route('test')
    ->action(admin_action("test.index"))
    ->register();

  Router::route('dashboard')
    ->action(admin_action("dashboard.dashboard"))
    ->view(admin_view("dashboard.dashboard"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission("dashboard.dashboard")
    ->register();

  // Account
  Router::route('account/profile')
    ->action(admin_action("account.profile"))
    ->view(admin_view("account.profile"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'account.profile')
    ->register();

  Router::route('account/settings')
    ->action(admin_action("account.settings"))
    ->view(admin_view("account.settings"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'account.settings')
    ->register();

  // Users
  Router::route('users')
    ->action(admin_action("users.list"))
    ->view(admin_view("users.list"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'users.list')
    ->register();

  Router::route('user/new')
    ->action(admin_action("users.new"))
    ->view(admin_view("users.new"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'users.new')
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
    ->permission( 'users.delete')
    ->register();

  // Roles
  Router::route('roles')
    ->middleware('auth_admin')
    ->permission( 'roles.list')
    ->action(admin_action("roles.list"))
    ->view(admin_view("roles.list"))
    ->layout(admin_layout())
    ->register();

  Router::route('rol/new')
    ->action(admin_action("roles.new"))
    ->view(admin_view("roles.new"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'roles.new')
    ->register();

  Router::route('rol/edit/{id}')
    ->action(admin_action("roles.edit"))
    ->view(admin_view("roles.edit"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'roles.edit')
    ->register();

  Router::route('rol/delete/{id}')
    ->action(admin_action("roles.delete"))
    ->middleware('auth_admin')
    ->permission( 'roles.delete')
    ->register();

  // Permisions
  Router::route('permissions')
    ->action(admin_action("permissions.list"))
    ->view(admin_view("permissions.list"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'permissions.list')
    ->register();

  Router::route('permission/new')
    ->action(admin_action("permissions.new"))
    ->view(admin_view("permissions.new"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'permissions.new')
    ->register();

  Router::route('permission/edit/{id}')
    ->action(admin_action("permissions.edit"))
    ->view(admin_view("permissions.edit"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'permissions.edit')
    ->register();

  Router::route('permission/delete/{id}')
    ->action(admin_action("permissions.delete"))
    ->middleware('auth_admin')
    ->permission( 'permissions.delete')
    ->register();

  // Settings
  Router::route('settings/general')
    ->action(admin_action("settings.general"))
    ->view(admin_view("settings.general"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.general')
    ->register();

  Router::route('settings/backups')
    ->action(admin_action("settings.backups"))
    ->view(admin_view("settings.backups"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.backups')
    ->register();

  Router::route('settings/brand')
    ->action(admin_action("settings.brand"))
    ->view(admin_view("settings.brand"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.brand')
    ->register();

  Router::route('settings/captcha')
    ->action(admin_action("settings.captcha"))
    ->view(admin_view("settings.captcha"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.captcha')
    ->register();

  Router::route('settings/date_time')
    ->action(admin_action("settings.date_time"))
    ->view(admin_view("settings.date_time"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.date_time')
    ->register();

  Router::route('settings/info')
    ->action(admin_action("settings.info"))
    ->view(admin_view("settings.info"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.info')
    ->register();

  Router::route('settings/robots')
    ->action(admin_action("settings.robots"))
    ->view(admin_view("settings.robots"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.robots')
    ->register();

  Router::route('settings/sitemap')
    ->action(admin_action("settings.sitemap"))
    ->view(admin_view("settings.sitemap"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.sitemap')
    ->register();

  Router::route('settings/smtp')
    ->action(admin_action("settings.smtp"))
    ->view(admin_view("settings.smtp"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.smtp')
    ->register();

  Router::route('settings/social')
    ->action(admin_action("settings.social"))
    ->view(admin_view("settings.social"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.social')
    ->register();

  Router::route('settings/visitors')
    ->action(admin_action("settings.visitors"))
    ->view(admin_view("settings.visitors"))
    ->layout(admin_layout())
    ->middleware('auth_admin')
    ->permission( 'settings.visitors')
    ->register();
});

