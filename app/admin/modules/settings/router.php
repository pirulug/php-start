<?php

Router::route('settings/formats')
  ->action('settings@formats')
  ->view('settings@formats')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.formats')
  ->register();

Router::route('settings/general')
  ->action('settings@general')
  ->view('settings@general')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.general')
  ->register();

Router::route('settings/social')
  ->action('settings@social')
  ->view('settings@social')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.social')
  ->register();

Router::route('settings/robots')
  ->action('settings@robots')
  ->view('settings@robots')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.robots')
  ->register();

Router::route('settings/sitemap')
  ->action('settings@sitemap')
  ->view('settings@sitemap')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.sitemap')
  ->register();

Router::route('settings/brand')
  ->action('settings@brand')
  ->view('settings@brand')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.brand')
  ->register();

Router::route('settings/info')
  ->action('settings@info')
  ->view('settings@info')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.info')
  ->register();

Router::route('settings/backup')
  ->action('settings@backup')
  ->view('settings@backup')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.backup')
  ->register();

Router::route('settings/captcha')
  ->action('settings@captcha')
  ->view('settings@captcha')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.captcha')
  ->register();

Router::route('settings/smtp')
  ->action('settings@smtp')
  ->view('settings@smtp')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('settings.smtp')
  ->register();
