<?php

/**
 * Realiza un UPSERT (Insertar o Actualizar) en una tabla de metadatos.
 *
 * @param string $table Nombre de la tabla.
 * @param string $key_col Nombre de la columna de la llave.
 * @param string $val_col Nombre de la columna del valor.
 * @param string $key Valor de la llave.
 * @param mixed $value Valor a guardar (se convertirá a string).
 */
function meta_upsert($table, $key_col, $val_col, $key, $value) {
  // Variables intermedias para bindParam (evitar problemas de paso por referencia)
  $final_key = (string) $key;
  $final_val = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

  $sql = "INSERT INTO {$table} ({$key_col}, {$val_col}) 
          VALUES (:key, :val) 
          ON DUPLICATE KEY UPDATE {$val_col} = VALUES({$val_col})";

  $stmt = connect()->prepare($sql);
  $stmt->bindParam(':key', $final_key);
  $stmt->bindParam(':val', $final_val);
  $stmt->execute();
}

