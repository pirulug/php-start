<?php

Router::route('signin')
  ->action('auth@signin')
  ->view('auth@signin')
  ->layout('main')
  ->register();

Router::route('signup')
  ->action('auth@signup')
  ->view('auth@signup')
  ->layout('main')
  ->register();

Router::route('signout')
  ->action('auth@signout')
  ->register();

Router::route('reset-password')
  ->view('auth@reset')
  ->layout('main')
  ->register();

Router::route('reset-password/confirm/{token}')
  ->action('auth@reset_password')
  ->view('auth@reset_password')
  ->layout('main')
  ->register();

// Endpoint
Router::route('auth/check-autologin')
  ->endpoint('auth@check-autologin')
  ->register();