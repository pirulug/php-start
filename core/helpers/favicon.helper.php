<?php

function favicon(PDO $connect, string $table = 'options'): ?object {
  $sql = "SELECT option_value 
          FROM {$table} 
          WHERE option_key = 'favicon' 
          LIMIT 1";

  $stmt = $connect->prepare($sql);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_OBJ);

  if (!$row || !$row->option_value) {
    return null;
  }

  $json = json_decode($row->option_value);

  return (json_last_error() === JSON_ERROR_NONE) ? $json : null;
}
