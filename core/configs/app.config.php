<?php

// --------------------------------------------------------------------------
// SECCIÓN: CONFIGURACIÓN DE MÓDULOS DEL SISTEMA
// --------------------------------------------------------------------------

/**
 * Activa o desactiva el acceso al módulo de Frontend.
 * Si está desactivado, cualquier acceso al front devolverá 404.
 */
const ENABLE_FRONT = true;

/**
 * Activa o desactiva el acceso a la API global.
 * Si está desactivado, cualquier acceso a /api devolverá 404.
 */
const ENABLE_API = true;

/**
 * Idioma por defecto de la aplicación.
 * Se utilizará como fallback si no se ha configurado un idioma en la base de datos o en la sesión.
 */
const DEFAULT_LANG = "es";
