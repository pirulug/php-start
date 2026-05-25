<?php

Router::route('account/profile')
  ->middleware("auth_home")
  ->permission("account.profile")
  ->action('account@profile')
  ->view('account@profile')
  ->layout('main')
  ->register();

Router::route('account/settings/profile')
  ->middleware("auth_home")
  ->permission("account.edit")
  ->action('account@settings_profile')
  ->view('account@settings_profile')
  ->layout('main')
  ->register();

Router::route('account/settings/password')
  ->middleware("auth_home")
  ->permission("account.edit")
  ->action('account@settings_password')
  ->view('account@settings_password')
  ->layout('main')
  ->register();

Router::route('account/settings/2fa')
  ->middleware("auth_home")
  ->permission("account.edit")
  ->action('account@settings_2fa')
  ->view('account@settings_2fa')
  ->layout('main')
  ->register();

Router::route('account/settings/api')
  ->middleware("auth_home")
  ->permission("account.edit")
  ->action('account@settings_api')
  ->view('account@settings_api')
  ->layout('main')
  ->register();