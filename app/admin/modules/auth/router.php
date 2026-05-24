<?php

// Loggerar
Router::route('sign-in')
  ->action('auth@sign-in')
  ->view('auth@sign-in')
  ->layout('auth')
  ->register();

Router::route('sign-out')
  ->action('auth@sign-out')
  ->register();

Router::route('2fa-code')
  ->action('auth@2fa-code')
  ->view('auth@2fa-code')
  ->layout('auth')
  ->register();
