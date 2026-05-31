<?php

Router::route("modules")
  ->action("modules@admin")
  ->view("modules@admin")
  ->layout("main")
  ->permission("modules.admin")
  ->register();

Router::route("modules/front")
  ->action("modules@front")
  ->view("modules@front")
  ->layout("main")
  ->permission("modules.front")
  ->register();

Router::route("modules/api")
  ->action("modules@api")
  ->view("modules@api")
  ->layout("main")
  ->permission("modules.api")
  ->register();
