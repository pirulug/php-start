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

Router::route('reset-password')
  ->analytic("Recuperar Contraseña")
  ->view(home_view("auth.reset"))
  ->layout(home_layout())
  ->register();


Router::route('reset-password/confirm/{token}')
  ->analytic("Establecer Nueva Contraseña")
  ->action(home_action("auth.reset_password"))
  ->view(home_view("auth.reset_password"))
  ->layout(home_layout())
  ->register();

