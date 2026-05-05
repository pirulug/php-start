<?php

/**
 * Obtiene la instancia global de la base de datos (PDO).
 * 
 * @return PDO
 */
function connect() {
  global $connect;
  return $connect;
}

/**
 * Obtiene la instancia global de la configuración del sitio.
 * 
 * @return SiteConfig
 */
function site_config() {
  global $config;
  return $config;
}

/**
 * Obtiene la instancia global del cifrado y seguridad.
 * 
 * @return Cipher
 */
function cipher() {
  global $cipher;
  return $cipher;
}

/**
 * Obtiene la instancia global del gestor de fechas.
 * 
 * @return SiteDate
 */
function site_date() {
  global $site_date;
  return $site_date;
}
/**
 * Obtiene la sesión del usuario actual si existe.
 * 
 * @return stdClass|null
 */
function user_session() {
  global $user_session;
  return $user_session;
}
/**
 * Obtiene los datos de la ruta actual resuelta por el motor de enrutamiento.
 * 
 * @return array|null
 */
function route() {
  global $route;
  return $route;
}
