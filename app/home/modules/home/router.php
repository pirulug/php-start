<?php

Router::route('/')
  ->analytic('Home')
  ->action(home_action('home.index'))
  ->view(home_view('home.index'))
  ->layout(home_layout())
  ->register();