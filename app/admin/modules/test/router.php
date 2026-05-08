<?php

Router::route('test')
  ->action('test@list')->view('test@list')->layout('main')->register();

Router::route('test/new')
  ->action('test@new')->view('test@new')->layout('main')->register();

Router::route('test/edit/{id}')
  ->action('test@edit')->view('test@edit')->layout('main')->register();

Router::route('test/delete/{id}')
  ->action('test@delete')->register();

Router::route('test/deactivate/{id}')
  ->action('test@deactivate')->register();
