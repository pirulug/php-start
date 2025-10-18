<?php

// Theme Render
$theme->render(
  BASE_DIR_FRONT . "/views/account/profile.view.php",
  [
    'theme_title' => 'Perfil',
    'theme_path'  => 'profile',
  ],
  BASE_DIR_FRONT . "/views/layouts/app.layout.php"
);