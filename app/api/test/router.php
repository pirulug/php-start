<?php

Router::route('test/list')
  ->action('test@list')->register();

Router::route('test/get/{id}')
  ->action('test@get')->register();

Router::route('test/create')
  ->action('test@create')->register();

Router::route('test/update/{id}')
  ->action('test@update')->register();

Router::route('test/delete/{id}')
  ->action('test@delete')->register();
