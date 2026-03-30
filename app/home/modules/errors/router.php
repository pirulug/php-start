<?php

Router::route('404')
  ->analytic('Página no encontrada')
  ->action(home_action('errors.404'))
  ->view(home_view('errors.404'))
  ->layout(home_layout())
  ->register();
