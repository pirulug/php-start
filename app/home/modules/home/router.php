<?php

Router::route('/')
  ->analytic('Home')
  ->action(BASE_DIR . "/app/home/modules/home/actions/index.action.php")
  ->view(BASE_DIR . "/app/home/modules/home/views/index.view.php")
  ->layout(BASE_DIR . "/app/home/layouts/main.layout.php")
  ->register();