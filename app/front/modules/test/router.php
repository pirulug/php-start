<?php

Router::route('test')
  ->action('test@index')
  ->view('test@index')
  ->layout('main')
  ->register();
