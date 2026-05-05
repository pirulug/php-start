<?php

// =============================================================================
// SECCIÓN: CONFIGURACIÓN Y AYUDA
// =============================================================================

if (in_array('--help', $argv) || in_array('-h', $argv)) {
  echo "\nReset de Identidad Visual\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps reset-logos\n\n";
  echo "Descripción:\n";
  echo "  1. Limpia y repone los archivos originales en storage/uploads/site/.\n";
  echo "  2. Restaura los valores por defecto en la tabla 'options'.\n";
  echo "--------------------------------------------------------\n\n";
  exit();
}

// Configuración de rutas
$sourceDir = BASE_DIR . '/core/comander/images/site';
$targetDir = BASE_DIR . '/storage/uploads/site';

/**
 * Copia recursiva de directorios.
 */
function copy_recursive($source, $dest) {
  if (is_dir($source)) {
    if (!is_dir($dest))
      mkdir($dest, 0777, true);
    $files = scandir($source);
    foreach ($files as $file) {
      if ($file != "." && $file != "..") {
        copy_recursive("$source/$file", "$dest/$file");
      }
    }
  } elseif (is_file($source)) {
    copy($source, $dest);
  }
}

/**
 * Limpieza recursiva de directorios.
 */
function clean_dir_recursive($dir) {
  if (!is_dir($dir))
    return;
  $files = array_diff(scandir($dir), array('.', '..'));
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? clean_dir_recursive("$dir/$file") : @unlink("$dir/$file");
  }
}

// =============================================================================
// SECCIÓN: LÓGICA DE EJECUCIÓN
// =============================================================================

echo "\n[SOLICITADO] Iniciando Reseteo de Identidad\n";
echo "--------------------------------------------------------\n";

// 1. Restauración de Archivos Físicos
echo "PROCESANDO: Limpiando directorio de subidas... ";
clean_dir_recursive($targetDir);
echo "[OK]\n";

if (is_dir($sourceDir)) {
  echo "PROCESANDO: Restaurando activos originales... ";
  copy_recursive($sourceDir, $targetDir);
  echo "[OK]\n";
} else {
  echo "[ERROR] No se encontraron los activos originales.\n";
  echo "RUTA: $sourceDir\n";
  exit();
}

// 2. Actualización de Base de Datos
try {
  $defaults = [
    'dark_logo'  => 'st_logo_dark.webp',
    'white_logo' => 'st_logo_light.webp',
    'og_image'   => 'og_image.webp',
    'favicon'    => json_encode([
      "android-chrome-192x192" => "android-chrome-192x192.png",
      "android-chrome-512x512" => "android-chrome-512x512.png",
      "apple-touch-icon"       => "apple-touch-icon.png",
      "favicon-16x16"          => "favicon-16x16.png",
      "favicon-32x32"          => "favicon-32x32.png",
      "favicon.ico"            => "favicon.ico",
      "webmanifest"            => "site.webmanifest"
    ])
  ];

  foreach ($defaults as $key => $val) {
    $stmt = $connect->prepare("INSERT INTO options (option_key, option_value) 
                               VALUES (?, ?) 
                               ON DUPLICATE KEY UPDATE option_value = VALUES(option_value)");
    $stmt->execute([$key, $val]);
  }

  echo "PROCESANDO: Actualizando base de datos... [OK]\n";
} catch (Exception $e) {
  echo "[ERROR] Fallo al actualizar la base de datos.\n";
  echo "DETALLE: " . $e->getMessage() . "\n";
  exit();
}

// 3. Limpieza de Caché
$cacheFile = BASE_DIR . '/storage/caches/site_options.cache.php';
if (file_exists($cacheFile)) {
  @unlink($cacheFile);
  echo "PROCESANDO: Limpiando caché... [OK]\n";
}

echo "--------------------------------------------------------\n";
echo "[OK] El sitio ha recuperado su identidad visual original.\n\n";