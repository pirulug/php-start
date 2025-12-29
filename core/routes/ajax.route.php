<?php


Router::prefix(PATH_AJAX, CTX_AJAX, function () {

  // header('Content-Type: application/json; charset=utf-8');

  Router::get('visitors')
    ->action(BASE_DIR . "/app/ajax/visitors.php");

});