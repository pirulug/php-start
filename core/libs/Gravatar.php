<?php

/**
 * Obtiene el enlace o la imagen de un avatar de Gravatar basado en el correo electrónico proporcionado.
 *
 * @param string $email El correo electrónico del usuario para generar el avatar.
 * @param int $s El tamaño del avatar en píxeles. El valor predeterminado es 150.
 * @param string $d La imagen predeterminada a usar si no existe el avatar. Puede ser:
 *                  - '404': No mostrar nada si no hay avatar.
 *                  - 'mp': Mostrar un avatar por defecto (mistery person).
 *                  - 'identicon', 'monsterid', 'wavatar', 'retro', 'robohash', etc.
 * @param string $r La calificación máxima permitida para el avatar. Valores posibles:
 *                  - 'g': General (apto para todos).
 *                  - 'pg': Supervisión parental.
 *                  - 'r': Restringido.
 *                  - 'x': Contenido extremo.
 * @param bool $img Si es `true`, devuelve una etiqueta `<img>` con el avatar. Si es `false`, devuelve solo la URL.
 * @param array $atts Un array asociativo con atributos adicionales para la etiqueta `<img>` (por ejemplo, 'class', 'alt').
 *
 * @return string La URL del avatar o una etiqueta `<img>` con el avatar según el parámetro `$img`.
 *
 * @example
 * // Obtener solo la URL del avatar
 * echo getGravatar('user@example.com');
 *
 * // Obtener un avatar como imagen con atributos adicionales
 * echo getGravatar('user@example.com', 200, 'retro', 'pg', true, ['class' => 'avatar', 'alt' => 'User Avatar']);
 */
function get_gravatar($email, $s = 150, $d = 'mp', $r = 'g', $img = false, $atts = array()) {
  $url = 'https://www.gravatar.com/avatar/';
  $url .= md5(strtolower(trim($email)));
  $url .= "?s=$s&d=$d&r=$r";
  if ($img) {
    $url = '<img src="' . $url . '"';
    foreach ($atts as $key => $val)
      $url .= ' ' . $key . '="' . $val . '"';
    $url .= ' />';
  }
  return $url;
}