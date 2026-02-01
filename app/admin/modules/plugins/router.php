<?php

Router::route('plugins/datatable')
  ->action(admin_action("plugins.datatable"))
  ->view(admin_view("plugins.datatable"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("plugins.datatable")
  ->register();