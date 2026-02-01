<?php

Router::prefix(PATH_ADMIN, CTX_ADMIN, function () {

  loadAdminRoutes();

  Router::route('')
    ->action(admin_action("auth.login"))
    ->view(admin_view("auth.login"))
    ->layout(admin_layout("auth"))
    ->register();
});
