<?php

Router::route('404')
  ->action('errors@404')
  ->view('errors@404')
  ->layout('main')
  ->register();