<?php

Router::route("users")
  ->action("users@list")
  ->view("users@list")
  ->layout("main")
  ->middleware("auth_admin")
  ->permission("users.list")
  ->register();

Router::route("user/new")
  ->action("users@new")
  ->view("users@new")
  ->layout("main")
  ->middleware("auth_admin")
  ->permission("users.new")
  ->register();

Router::route("user/edit/{id}")
  ->action("users@edit")
  ->view("users@edit")
  ->layout("main")
  ->middleware("auth_admin")
  ->permission("users.edit")
  ->register();

Router::route("user/deactivate/{id}")
  ->action("users@deactivate")
  ->middleware("auth_admin")
  ->permission("users.deactivate")
  ->register();

Router::route("user/delete/{id}")
  ->action("users@delete")
  ->middleware("auth_admin")
  ->permission("users.delete")
  ->register();

Router::route("user/api/{id}")
  ->action("users@api")
  ->view("users@api")
  ->layout("main")
  ->middleware("auth_admin")
  ->permission("users.api")
  ->register();

Router::route("user/permissions/{id}")
  ->action("users@permissions")
  ->view("users@permissions")
  ->layout("main")
  ->middleware("auth_admin")
  ->permission("users.permissions")
  ->register();

// Endpoints
Router::route("users/endpoint/list")
  ->endpoint("users@list")
  ->middleware("auth_admin")
  ->permission("users.list")
  ->register();