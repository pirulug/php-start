<?php

Router::route('settings/general')
  ->action(admin_action("settings.general"))
  ->view(admin_view("settings.general"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.general')
  ->register();

Router::route('settings/options')
  ->action(admin_action("settings.options"))
  ->view(admin_view("settings.options"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.options')
  ->register();

Router::route('settings/backups')
  ->action(admin_action("settings.backups"))
  ->view(admin_view("settings.backups"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.backups')
  ->register();

Router::route('settings/brand')
  ->action(admin_action("settings.brand"))
  ->view(admin_view("settings.brand"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.brand')
  ->register();

Router::route('settings/captcha')
  ->action(admin_action("settings.captcha"))
  ->view(admin_view("settings.captcha"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.captcha')
  ->register();

Router::route('settings/date_time')
  ->action(admin_action("settings.date_time"))
  ->view(admin_view("settings.date_time"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.date_time')
  ->register();

Router::route('settings/info')
  ->action(admin_action("settings.info"))
  ->view(admin_view("settings.info"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.info')
  ->register();

Router::route('settings/robots')
  ->action(admin_action("settings.robots"))
  ->view(admin_view("settings.robots"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.robots')
  ->register();

Router::route('settings/sitemap')
  ->action(admin_action("settings.sitemap"))
  ->view(admin_view("settings.sitemap"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.sitemap')
  ->register();

Router::route('settings/smtp')
  ->action(admin_action("settings.smtp"))
  ->view(admin_view("settings.smtp"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.smtp')
  ->register();

Router::route('settings/social')
  ->action(admin_action("settings.social"))
  ->view(admin_view("settings.social"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('settings.social')
  ->register();
