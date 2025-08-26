<?php
require_once "core/init.php";

$theme->render(
  BASE_DIR_PAGES . "/views/index.view.php",
  [
    'theme_title' => 'Inicio',
    'theme_path'  => 'inicio',
  ],
  BASE_DIR_PAGES . "/views/layouts/app.layout.php"
);