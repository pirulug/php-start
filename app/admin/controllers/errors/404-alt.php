<?php

require_once "core/init.php";

// echo "Hola mundo";

$theme->render(
  BASE_DIR_ADMIN . "/views/errors/404-alt.view.php",
  [
    'theme_title' => '404',
    'theme_path'  => '404',
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);
