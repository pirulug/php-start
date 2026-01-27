<?php


Router::prefix(PATH_AJAX, CTX_AJAX, function () {

  Router::route('visitors')
    ->action(BASE_DIR . "/app/ajax/visitors.php")
    ->register();

  Router::route("mail")
    ->action(BASE_DIR . "/app/ajax/mail.php")
    ->register();

  Router::route("datatable")
    ->action(BASE_DIR . "/app/ajax/datatable.php")
    ->middleware("auth_ajax")
    ->register();

  Router::route("country")
    ->action(BASE_DIR . "/app/ajax/country.php")
    ->register();
});