<?php

Router::route('account/profile')
  ->action(admin_action("account.profile"))
  ->view(admin_view("account.profile"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('account.profile')
  ->register();

Router::route('account/settings')
  ->action(admin_action("account.settings"))
  ->view(admin_view("account.settings"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission('account.settings')
  ->register();