<?php
/**
 * Endpoint para listar usuarios.
 * Retorna un objeto JSON con el éxito de la operación y los datos de los usuarios.
 */

echo json_encode([
  'success' => true,
  "message" => "Listado de usuarios"
]);

exit();