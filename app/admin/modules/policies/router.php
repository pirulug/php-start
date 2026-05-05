<?php

/**
 * Enrutamiento del Módulo de Políticas.
 */

// LISTADO
Router::route('policies')
  ->action('policies@list')
  ->view('policies@list')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("policies.list")
  ->register();

// NUEVO
Router::route('policy/new')
  ->action('policies@new')
  ->view('policies@new')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("policies.new")
  ->register();

// EDICIÓN
Router::route('policy/edit/{id}')
  ->action('policies@edit')
  ->view('policies@edit')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("policies.edit")
  ->register();

// ELIMINACIÓN
Router::route('policy/delete/{id}')
  ->action('policies@delete')
  ->middleware('auth_admin')
  ->permission("policies.delete")
  ->register();

// ESTADO (ACTIVAR/DESACTIVAR)
Router::route('policy/status/{id}')
  ->action('policies@status')
  ->middleware('auth_admin')
  ->permission("policies.status")
  ->register();