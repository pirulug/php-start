<?php

Router::route('account/profile')
  ->analytic("Mi Perfil")
  ->middleware("auth_home")
  ->permission("account.profile")
  ->action(home_action("account.profile"))
  ->view(home_view("account.profile"))
  ->layout(home_layout())
  ->register();

Router::route('account/settings/profile')
  ->analytic("Configuración de Perfil")
  ->middleware("auth_home")
  ->permission("account.edit")
  ->action(home_action("account.settings_profile"))
  ->view(home_view("account.settings_profile"))
  ->layout(home_layout())
  ->register();

Router::route('account/settings/password')
  ->analytic("Seguridad de la Cuenta")
  ->middleware("auth_home")
  ->permission("account.edit")
  ->action(home_action("account.settings_password"))
  ->view(home_view("account.settings_password"))
  ->layout(home_layout())
  ->register();