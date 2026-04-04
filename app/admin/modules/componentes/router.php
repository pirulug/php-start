<?php

Router::route('componentes/datatable')
  ->action(admin_action("componentes.datatable"))
  ->view(admin_view("componentes.datatable"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("roles.new")
  ->register();

Router::route('componentes/captcha')
  ->action(admin_action("componentes.captcha"))
  ->view(admin_view("componentes.captcha"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("roles.new")
  ->register();

Router::route('componentes/sweetalert')
  ->action(admin_action("componentes.sweetalert"))
  ->view(admin_view("componentes.sweetalert"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("roles.new")
  ->register();