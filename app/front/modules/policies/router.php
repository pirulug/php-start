<?php

/**
 * Enrutamiento Público de Políticas.
 * Captura slugs dinámicos en la raíz del sitio.
 */

Router::route('{slug}')
  ->action('policies@view')
  ->view('policies@view')
  ->layout('main')
  ->register();