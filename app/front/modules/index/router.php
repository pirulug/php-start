<?php

Router::route('/')
  ->action('index@index')
  ->view('index@index')
  ->layout('main')
  ->register();

Router::route('lang/{lang}')
  ->action('index@set_lang')
  ->register();