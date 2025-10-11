<?php
header("Content-Type: application/json");

switch ($segments[1] ?? '') {
  case 'test':
    switch ($segments[2] ?? '') {
      case 'mail':
        include path_ajax("test-mail");
        break;

      default:
        http_response_code(404);
        echo json_encode([
          "success" => false,
          "message" => "Ruta AJAX no encontrada"
        ]);
        break;
    }
    break;

  case 'save':
    // Aquí manejarías POST/PUT
    echo json_encode([
      "success" => true,
      "message" => "Save Encontrado"
    ]);
    break;

  default:
    http_response_code(404);
    echo json_encode([
      "success" => false,
      "message" => "Ruta AJAX no encontrada"
    ]);
    break;
}
