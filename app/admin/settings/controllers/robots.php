<?php

$file_path = BASE_DIR . "/robots.txt";

// Verificar si el archivo existe
if (file_exists($file_path)) {
  $file_content = file_get_contents($file_path);
} else {
  $file_content = "";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $content = $_POST["content"];
  file_put_contents($file_path, $content);

  $notifier->add('Se actualizo de manera correcta', 'success', "toast");
  header("Location:" . $_SERVER['REQUEST_URI']);
  exit();
}

