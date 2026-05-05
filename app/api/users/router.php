<?php

Router::route('users/list')
  ->action('users@list')->register();

Router::route('users/get/{id}')
  ->action('users@get')->register();

Router::route('users/create')
  ->action('users@create')->register();

Router::route('users/update/{id}')
  ->action('users@update')->register();

Router::route('users/delete/{id}')
  ->action('users@delete')->register();
