<?php

Router::route('account/profile')
  ->action('account@profile')
  ->view('account@profile')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('account.profile')
  ->register();

Router::route('account/settings/profile')
  ->action('account@settings_profile')
  ->view('account@settings_profile')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('account.settings')
  ->register();

Router::route('account/settings/password')
  ->action('account@settings_password')
  ->view('account@settings_password')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('account.settings')
  ->register();

Router::route('account/settings/api')
  ->action('account@settings_api')
  ->view('account@settings_api')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('account.settings')
  ->register();

Router::route('account/lang/{lang}')
  ->action('account@set_lang')
  ->register();