<?php

Router::route("logs")
  ->action("logs@list")
  ->view("logs@list")
  ->layout("main")
  ->middleware("auth_admin")
  ->permission("logs.list")
  ->register();

Router::route("log/view")
  ->action("logs@view")
  ->view("logs@view")
  ->layout("main")
  ->middleware("auth_admin")
  ->permission("logs.list")
  ->register();
