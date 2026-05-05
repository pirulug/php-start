<?php

/**
 * Actualiza una única opción en la tabla 'options'.
 *
 * @param string $key Llave de la opción.
 * @param mixed $value Valor de la opción.
 */
function meta_options_upsert($key, $value) {
  meta_upsert('options', 'option_key', 'option_value', $key, $value);
  site_config()->refresh();
}

/**
 * Actualiza múltiples opciones en la tabla 'options' de forma masiva.
 *
 * @param array $data Array asociativo [llave => valor].
 */
function meta_options_upsert_many(array $data) {
  foreach ($data as $key => $value) {
    meta_upsert('options', 'option_key', 'option_value', $key, $value);
  }

  site_config()->refresh();
}
