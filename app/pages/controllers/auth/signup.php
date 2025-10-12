<?php

// Theme Render
$theme->render(
  BASE_DIR_FRONT . "/views/auth/signup.view.php",
  [
    'theme_title' => 'Registro',
    'theme_path'  => 'signup',
  ],
  BASE_DIR_FRONT . "/views/layouts/app.layout.php"
);