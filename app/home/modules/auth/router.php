<?php

Router::route('signin')
  ->action(home_action("auth.signin"))
  ->view(home_view("auth.signin"))
  ->layout(home_layout())
  ->register();

Router::route('signup')
  ->action(home_action("auth.signup"))
  ->view(home_view("auth.signup"))
  ->layout(home_layout())
  ->register();

Router::route('signout')
  ->action(home_action("auth.signout"))
  ->register();
