<?php

Router::route('visitors')
  ->action(ajax_action('analytics.visitors'))
  ->register();

Router::route("country")
  ->action(ajax_action("analytics.country"))
  ->register();