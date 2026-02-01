<?php

Router::route('dashboard')
  ->action(admin_action("dashboard.dashboard"))
  ->view(admin_view("dashboard.dashboard"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("dashboard.dashboard")
  ->register();