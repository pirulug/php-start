<?php
switch ($segments[0]) {
  case '':
    include path_front("index");
    break;

  case 'about':
    echo "Acerca de";
    // include "pages/about.php";
    break;

  default:
    http_response_code(404);
    echo "Página no encontrada (Front)";
    break;
}
