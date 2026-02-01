<?php

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