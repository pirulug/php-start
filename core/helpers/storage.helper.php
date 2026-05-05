<?php

// Cargar uploads
function storage_uploads($file, $dir = "") {
  if ($dir == "") {
    return APP_URL . "/storage/uploads/{$file}";
  }
  
  return APP_URL . "/storage/uploads/{$dir}/{$file}";
}
