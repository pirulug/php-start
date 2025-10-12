<?php

// Theme Render
$theme->render(
  BASE_DIR_FRONT . "/views/errors/404.view.php",
  [
    'theme_title' => '404',
    'theme_path'  => '404',
  ],
  BASE_DIR_FRONT . "/views/layouts/app.layout.php"
);