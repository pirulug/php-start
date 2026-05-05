<?php

// --------------------------------------------------------------------------
// SECCIÓN: COMPONENTES DE FORMULARIO (TIMEZONE)
// --------------------------------------------------------------------------

/**
 * Genera el contenido HTML de un elemento <select> con las zonas horarias del mundo.
 * Organizado por continentes y con soporte para UTC y offsets manuales.
 *
 * @param string $selected La zona horaria seleccionada por defecto.
 * @return string HTML de las opciones (<optgroup> y <option>).
 */
function select_timezone($selected = '') {
  // Lista de continentes principales
  $continents = ['Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific'];
  $timezones  = timezone_identifiers_list();

  $structure = '';

  foreach ($continents as $continent) {
    $group_options = '';

    foreach ($timezones as $tz) {
      if (strpos($tz, $continent . '/') !== 0) {
        continue;
      }

      $city = str_replace($continent . '/', '', $tz);
      $city = str_replace('_', ' ', $city);

      $selected_attr  = ($tz === $selected) ? ' selected' : '';
      $group_options .= "<option value=\"$tz\"$selected_attr>$city</option>\n";
    }

    if ($group_options !== '') {
      $structure .= "<optgroup label=\"$continent\">\n$group_options</optgroup>\n";
    }
  }

  // Grupo UTC
  $structure     .= "<optgroup label=\"UTC\">\n";
  $selected_attr  = ($selected === 'UTC') ? ' selected' : '';
  $structure     .= "<option value=\"UTC\"$selected_attr>UTC</option>\n";
  $structure     .= "</optgroup>\n";

  // Desplazamientos manuales (-12 a +14)
  $structure .= "<optgroup label=\"Desplazamientos Manuales\">\n";
  for ($i = -12; $i <= 14; $i += 0.5) {
    $sign           = ($i >= 0 ? '+' : '');
    $label          = 'UTC' . $sign . (fmod($i, 1) === 0.5 ? ($i < 0 ? $i + 0.5 : $i) - 0.5 . ':30' : $i);
    $value          = 'UTC' . $sign . $i;
    $selected_attr  = ($selected === $value) ? ' selected' : '';
    $structure     .= "<option value=\"$value\"$selected_attr>$label</option>\n";
  }
  $structure .= "</optgroup>\n";

  return $structure;
}