<?php

Router::route('plugins/datatable')
  ->action(admin_action("plugins.datatable"))
  ->view(admin_view("plugins.datatable"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("plugins.datatable")
  ->register();

Router::route('plugins/captcha')
  ->action(admin_action("plugins.captcha"))
  ->view(admin_view("plugins.captcha"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("plugins.captcha")
  ->register();

Router::route('plugins/img')
  ->action(admin_action("plugins.img"))
  ->register();