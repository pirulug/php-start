<?php

Router::route('install')
  ->action(BASE_DIR . "/app/install/index.php")
  ->register();

Router::route('install/restore')
  ->action(BASE_DIR . "/app/install/index.php")
  ->register();

Router::route('install/migrate')
  ->action(BASE_DIR . "/app/install/index.php")
  ->register();
