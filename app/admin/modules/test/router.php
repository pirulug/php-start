<?php

Router::route('test')
  ->action(admin_action("test.index"))
  ->view(admin_view("test.index"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->register();