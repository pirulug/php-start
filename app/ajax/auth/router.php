<?php

/**
 * Enrutador AJAX para el módulo de Autenticación.
 */

Router::route('auth/reset')
  ->action(ajax_action("auth.reset"))
  ->register();
