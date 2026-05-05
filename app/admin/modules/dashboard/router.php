<?php

Router::route('dashboard')
  ->action('dashboard@dashboard')
  ->view('dashboard@dashboard')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("dashboard.dashboard")
  ->register();