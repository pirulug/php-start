<?php

Router::route('login')
  ->action('auth@login')
  ->view('auth@login')
  ->layout('auth')
  ->register();

Router::route('logout')
  ->action('auth@logout')
  ->register();