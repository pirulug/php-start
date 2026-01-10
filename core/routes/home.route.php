<?php

Router::route('/')
  ->analytic('Home')
  ->action(BASE_DIR . "/app/home/modules/home/actions/index.action.php")
  ->view(BASE_DIR . "/app/home/modules/home/views/index.view.php")
  ->layout(BASE_DIR . "/app/home/layouts/main.layout.php")
  ->register();

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

Router::route('profile')
  ->analytic("Perfil")
  ->middleware("auth_home")
  ->permission("account.profile")
  ->action(home_action("account.profile"))
  ->view(home_view("account.profile"))
  ->layout(home_layout())
  ->register();

Router::route('profile/edit')
  ->analytic("Editar Perfil")
  ->middleware("auth_home")
  ->permission("account.edit")
  ->action(home_action("account.edit"))
  ->view(home_view("account.edit"))
  ->layout(home_layout())
  ->register();