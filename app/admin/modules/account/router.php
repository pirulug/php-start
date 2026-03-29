<?php

Router::route('account/profile')
  ->action(admin_action("account.profile"))
  ->view(admin_view("account.profile"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('account.profile')
  ->register();

Router::route('account/settings/profile')
  ->action(admin_action("account.settings_profile"))
  ->view(admin_view("account.settings_profile"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('account.settings')
  ->register();

Router::route('account/settings/password')
  ->action(admin_action("account.settings_password"))
  ->view(admin_view("account.settings_password"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('account.settings')
  ->register();