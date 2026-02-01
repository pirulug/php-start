<?php

Router::route('login')
  ->action(admin_action("auth.login"))
  ->view(admin_view("auth.login"))
  ->layout(admin_layout("auth"))
  ->register();

Router::route('logout')
  ->action(admin_action("auth.logout"))
  ->register();