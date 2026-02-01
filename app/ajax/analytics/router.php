<?php

Router::route('visitors')
  ->action(BASE_DIR . "/app/ajax/analytics/actions/visitors.php")
  ->register();

Router::route("country")
  ->action(BASE_DIR . "/app/ajax/analytics/actions/country.php")
  ->register();