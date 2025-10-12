<?php
switch ($segments[0]) {
  case '':
    include path_front("index");
    break;

  case 'signin':
    include path_front("auth/signin");
    break;

  case 'signup':
    include path_front("auth/signup");
    break;

  case 'signout':
    include path_front("auth/signout");
    break;

  default:
    http_response_code(404);
    include path_front("errors/404");
    break;
}
