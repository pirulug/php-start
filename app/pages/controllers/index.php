<?php

// Theme Render
$theme->render(
  BASE_DIR_FRONT . "/views/index.view.php",
  [
    'theme_title' => 'Inicio',
    'theme_path'  => 'inicio',
  ],
  BASE_DIR_FRONT . "/views/layouts/app.layout.php"
);