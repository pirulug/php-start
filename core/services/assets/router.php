<?php

// Script Loader
Router::route("/" . PATH_ADMIN . '/{module}/script/{file}.js')
  ->action(BASE_DIR . '/core/services/assets/serve_script.action.php')
  ->register();

// Automatic Endpoint Loader
Router::route("/" . PATH_ADMIN . '/{module}/endpoint/{file}')
  ->action(BASE_DIR . '/core/services/assets/serve_endpoint.action.php')
  ->middleware('auth_admin')
  ->register();

// --- FRONT ---

// Front Script Loader
Router::route('/{module}/script/{file}.js')
  ->action(BASE_DIR . '/core/services/assets/serve_front_script.action.php')
  ->register();

// Front Automatic Endpoint Loader
Router::route('/{module}/endpoint/{file}')
  ->action(BASE_DIR . '/core/services/assets/serve_front_endpoint.action.php')
  ->register();




