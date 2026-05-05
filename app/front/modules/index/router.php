<?php

Router::route('/')
  ->action('index@index')
  ->view('index@index')
  ->layout('main')
  ->register();