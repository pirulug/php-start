<?php
header("Content-Type: application/json");

switch ($segments[1] ?? '') {
  case 'get':
    switch ($segments[2] ?? '') {
      case 'users':
        include path_api("users");
        break;
    }
    break;

  case 'save-user':
    // Aquí manejarías POST/PUT
    echo json_encode(["status" => "ok"]);
    break;

  default:
    http_response_code(404);
    echo json_encode(["error" => "Ruta AJAX no encontrada"]);
    break;
}
