<?php

$theme->render(
  BASE_DIR_ADMIN . "/views/test.view.php",
  [
    'theme_title' => $theme_title,
    'theme_path'  => $theme_path,
  ],
  BASE_DIR_ADMIN . "/views/layouts/app.layout.php"
);

