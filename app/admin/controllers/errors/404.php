<?php

require_once "core/init.php";

// echo "Hola mundo";

$theme->render(
  BASE_DIR_ADMIN . "/views/errors/404.view.php",
  [
    'theme_title' => $theme_title,
    'theme_path'  => $theme_path,
  ],
  BASE_DIR_ADMIN . "/views/layouts/error.layout.php"
);
