<?php

Router::route('users')
  ->action(api_action('users.users'))
  ->register();