<?php

Router::route('users')
  ->action(BASE_DIR . "/app/api/users/actions/users.php")
  ->register();