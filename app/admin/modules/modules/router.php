<?php

Router::route("modules")
  ->action("modules@admin")
  ->view("modules@admin")
  ->layout("main")
  ->register();

Router::route("modules/front")
  ->action("modules@front")
  ->view("modules@front")
  ->layout("main")
  ->register();

Router::route("modules/api")
  ->action("modules@api")
  ->view("modules@api")
  ->layout("main")
  ->register();
