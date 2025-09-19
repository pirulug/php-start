<?php

$theme->render(
  BASE_DIR_ADMIN . "/views/test.view.php",
  [
    'theme_title' => 'Test',
    'theme_path'  => 'tes',
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);

