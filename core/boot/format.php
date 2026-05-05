<?php

/**
 * Centraliza las funciones de formateo para fechas, números y moneda.
 * Utiliza la configuración global del sitio (SiteConfig y SiteDate).
 */

// -----------------------------------------------------------------------------
// SECCIÓN: LÓGICA INTERNA (PRIVADA)
// -----------------------------------------------------------------------------

/**
 * Obtiene la instancia única de SiteDate para el formateo de fechas.
 * 
 * @return SiteDate
 */
function _get_site_date_instance() {
  static $instance = null;
  if ($instance === null) {
    $instance = new SiteDate();
  }
  return $instance;
}

// -----------------------------------------------------------------------------
// SECCIÓN: FECHAS Y HORAS
// -----------------------------------------------------------------------------

/**
 * Formatea una fecha según la configuración del sitio (Solo Fecha).
 *
 * @param mixed $date Fecha (string, timestamp o DateTime).
 * @return string
 */
function format_date($date = 'now') {
  return _get_site_date_instance()->format($date, 'date');
}

/**
 * Formatea una hora según la configuración del sitio (Solo Hora).
 *
 * @param mixed $date Fecha/Hora.
 * @return string
 */
function format_time($date = 'now') {
  return _get_site_date_instance()->format($date, 'time');
}

/**
 * Formatea fecha y hora según la configuración del sitio.
 *
 * @param mixed $date Fecha/Hora.
 * @return string
 */
function format_datetime($date = 'now') {
  return _get_site_date_instance()->format($date, 'datetime');
}

// -----------------------------------------------------------------------------
// SECCIÓN: NÚMEROS Y MONEDA
// -----------------------------------------------------------------------------

/**
 * Formatea un número de forma inteligente.
 * Muestra decimales solo si existen en el número original.
 *
 * @param mixed $number Número a formatear.
 * @return string
 */
function format_number($number = "") {
  global $config;

  if (empty($number) && $number !== 0 && $number !== '0') {
    return "0";
  }

  // Convertimos a string para analizar la estructura decimal
  $str_number = (string)$number;
  $parts = explode('.', $str_number);
  
  // Si hay punto decimal, contamos cuántos caracteres hay después
  $decimals = 0;
  if (count($parts) > 1) {
    $decimals = strlen($parts[1]);
  }

  $dec_point = $config->get('number_decimal_sep', '.');
  $thousands_sep = $config->get('number_thousand_sep', ' ');

  return number_format((float)$number, $decimals, $dec_point, $thousands_sep);
}

/**
 * Formatea un número forzando siempre la aparición de decimales.
 *
 * @param mixed $number Número a formatear.
 * @param int $decimals Cantidad de decimales a mostrar.
 * @return string
 */
function format_number_decimal($number = "", $decimals = null) {
  global $config;

  $decimals = $decimals ?? (int) $config->get('number_decimals', 2);
  $dec_point = $config->get('number_decimal_sep', '.');
  $thousands_sep = $config->get('number_thousand_sep', ' ');

  if (empty($number) && $number !== 0 && $number !== '0') {
    return number_format(0, $decimals, $dec_point, $thousands_sep);
  }
  return number_format((float)$number, $decimals, $dec_point, $thousands_sep);
}

/**
 * Formatea un número como moneda.
 *
 * @param float|int $number Monto.
 * @param string|null $symbol Símbolo de moneda (opcional).
 * @return string
 */
function format_money($number, $symbol = null) {
  global $config;

  $symbol = $symbol ?? $config->get('currency_symbol', '$');
  $position = $config->get('currency_position', 'before');
  $dec_point = $config->get('currency_decimal_sep', '.');
  $thousands_sep = $config->get('currency_thousand_sep', ',');
  $decimals = (int) $config->get('currency_decimals', 2);

  $formatted = number_format((float)$number, $decimals, $dec_point, $thousands_sep);

  if ($position === 'after') {
    return $formatted . " " . $symbol;
  }
  
  return $symbol . " " . $formatted;
}