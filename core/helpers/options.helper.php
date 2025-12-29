<?php

function options(PDO $connect, string $table = 'options'): object {
  $options = new stdClass();

  $sql  = "SELECT option_key, option_value FROM {$table}";
  $stmt = $connect->prepare($sql);
  $stmt->execute();

  $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

  foreach ($rows as $row) {

    $value = $row->option_value;

    // Detectar JSON válido y convertir a OBJ
    if (is_string($value)) {
      $json = json_decode($value);
      if (json_last_error() === JSON_ERROR_NONE) {
        $value = $json;
      }
    }

    $options->{$row->option_key} = $value;
  }

  return $options;
}
