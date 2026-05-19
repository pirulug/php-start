<?php

/**
 * Cambia el idioma de la sesión en el frontend.
 */

$lang = $args["lang"] ?? "es";
$allowed_langs = ["es", "en"];

if (in_array($lang, $allowed_langs)) {
  $_SESSION["site_lang"] = $lang;
}

// Redirigir de vuelta a la página anterior o a la página de inicio
$referer = $_SERVER["HTTP_REFERER"] ?? front_route();
header("Location: " . $referer);
exit();
