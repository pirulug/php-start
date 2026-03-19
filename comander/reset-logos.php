<?php
// comander/reset-logos.php

define('BASE_DIR', dirname(__DIR__, 1));

// Inicializar conexión a DB para CLI
require_once BASE_DIR . '/config.php';
require_once BASE_DIR . '/core/libs/DataBase.php';

try {
    $db = (new DataBase())
        ->host(DB_HOST)
        ->name(DB_NAME)
        ->user(DB_USER)
        ->password(DB_PASS);
    
    $connect = $db->getConnection();
} catch (Exception $e) {
    cli_error("Error conectando a BD: " . $e->getMessage());
}

// ======================
// Funciones Helper
// ======================

function cli_log(string $message): void {
  echo $message . PHP_EOL;
}

function cli_ok(string $message): void {
  cli_log("✔ " . $message);
}

function cli_error(string $message): void {
  cli_log("✖ " . $message);
  exit(1);
}

/**
 * Elimina una carpeta recursivamente
 */
function delete_dir(string $dir): void {
  if (!is_dir($dir)) {
    return;
  }

  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST
  );

  foreach ($it as $file) {
    $file->isDir()
      ? rmdir($file->getRealPath())
      : unlink($file->getRealPath());
  }

  rmdir($dir);
}

/**
 * Copia una carpeta completa
 */
function copy_dir(string $source, string $dest): void {
  if (!is_dir($source)) {
    throw new Exception("Directorio fuente no existe: {$source}");
  }

  if (!is_dir($dest)) {
    mkdir($dest, 0755, true);
  }

  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
  );

  foreach ($it as $item) {
    $target = $dest . DIRECTORY_SEPARATOR . $it->getSubPathName();

    if ($item->isDir()) {
      if (!is_dir($target)) {
        mkdir($target, 0755, true);
      }
    } else {
      if (!copy($item->getRealPath(), $target)) {
        throw new Exception("No se pudo copiar: {$target}");
      }
    }
  }
}

function updateOption(PDO $db, string $key, $value): void {
  $stmt = $db->prepare("UPDATE options SET option_value = :value WHERE option_key = :key");
  $stmt->bindValue(':value', is_array($value) ? json_encode($value) : $value, PDO::PARAM_STR);
  $stmt->bindValue(':key', $key, PDO::PARAM_STR);
  $stmt->execute();
}

// ======================
// MAIN SCRIPT
// ======================

cli_log("Iniciando reset de logos...");

try {

  // Actualizar opciones en BD
  $favicon = [
    "android-chrome-192x192" => "android-chrome-192x192.png",
    "android-chrome-512x512" => "android-chrome-512x512.png",
    "apple-touch-icon"       => "apple-touch-icon.png",
    "favicon-16x16"          => "favicon-16x16.png",
    "favicon-32x32"          => "favicon-32x32.png",
    "favicon.ico"            => "favicon.ico",
    "webmanifest"            => "site.webmanifest"
  ];

  updateOption($connect, 'favicon', json_encode($favicon, JSON_UNESCAPED_SLASHES));
  updateOption($connect, 'white_logo', 'st_logo_light.webp');
  updateOption($connect, 'dark_logo', 'st_logo_dark.webp');
  updateOption($connect, 'og_image', 'og_image.webp');

  cli_ok("Opciones de logos actualizadas en BD");

  // Eliminar uploads/site
  $uploadsSite = BASE_DIR . '/storage/uploads/site';
  delete_dir($uploadsSite);

  cli_ok("Carpeta uploads/site eliminada");

  // Copiar imágenes base
  $source = BASE_DIR . '/comander/images/site';
  $dest   = BASE_DIR . '/storage/uploads/site';

  copy_dir($source, $dest);

  cli_ok("Logos copiados correctamente");
  cli_log("Proceso finalizado correctamente.");

} catch (Exception $e) {
  cli_error($e->getMessage());
}
