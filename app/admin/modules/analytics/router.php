<?php

Router::route('analytics/summary')
  ->action(admin_action("analytics.summary"))
  ->view(admin_view("analytics.summary"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('analytics.summary')
  ->register();

Router::route('analytics/visitors')
  ->action(admin_action("analytics.visitors"))
  ->view(admin_view("analytics.visitors"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('analytics.visitors')
  ->register();

Router::route('analytics/views')
  ->action(admin_action("analytics.views"))
  ->view(admin_view("analytics.views"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('analytics.views')
  ->register();

Router::route('analytics/online')
  ->action(admin_action("analytics.online"))
  ->view(admin_view("analytics.online"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('analytics.online')
  ->register();

Router::route('analytics/top')
  ->action(admin_action("analytics.top"))
  ->view(admin_view("analytics.top"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('analytics.top')
  ->register();

Router::route('analytics/mapa')
  ->action(admin_action("analytics.mapa"))
  ->view(admin_view("analytics.mapa"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('analytics.mapa')
  ->register();