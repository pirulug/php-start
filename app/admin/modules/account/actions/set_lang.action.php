<?php

/**
 * Cambia el idioma de la sesión actual.
 */

$lang = $args['lang'] ?? 'es';
$allowed_langs = ['es', 'en'];

if (in_array($lang, $allowed_langs)) {
  $_SESSION['site_lang'] = $lang;
}

// Redirigir de vuelta a la página anterior o al dashboard
$referer = $_SERVER['HTTP_REFERER'] ?? admin_route('dashboard');
header("Location: " . $referer);
exit();
