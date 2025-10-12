<?php

// Theme Render
$theme->render(
  BASE_DIR_FRONT . "/views/auth/signin.view.php",
  [
    'theme_title' => 'Iniciar sesión',
    'theme_path'  => 'signin',
  ],
  BASE_DIR_FRONT . "/views/layouts/app.layout.php"
);