<?php

/**
 * =========================================================
 * ROUTER: API USERS
 * =========================================================
 * 
 * Punto de entrada para el endpoint /api/users del sistema.
 */

Router::route('users')
  ->action(api_action("users.users"))
  ->middleware('auth_api')
  ->register();