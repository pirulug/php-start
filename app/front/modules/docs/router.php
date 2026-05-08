<?php

Router::route('docs')
  ->action('docs@index')
  ->view('docs@index')
  ->layout('main')
  ->register();

Router::route('docs/{page}')
  ->action('docs@index')
  ->view('docs@index')
  ->layout('main')
  ->register();
