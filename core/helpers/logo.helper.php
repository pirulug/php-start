<?php

function logo(PDO $connect, string $table = 'options'): object {
  $logo = new stdClass();

  $sql = "SELECT option_key, option_value
          FROM {$table}
          WHERE option_key IN ('dark_logo', 'white_logo')";

  $stmt = $connect->prepare($sql);
  $stmt->execute();

  $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

  foreach ($rows as $row) {
    if ($row->option_key === 'dark_logo') {
      $logo->dark = $row->option_value;
    }

    if ($row->option_key === 'white_logo') {
      $logo->light = $row->option_value;
    }
  }

  return $logo;
}
