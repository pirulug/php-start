<h2>Actualizar íconos y logos</h2>

<?php
try {
  // ======================
  // Actualizar configuraciones en la BD
  // ======================
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

  updateOption($connect, 'white_logo', "st_logo_light.webp");
  updateOption($connect, 'dark_logo', "st_logo_dark.webp");
  updateOption($connect, 'og_image', "og_image.webp");

  // ======================
  // Eliminar carpeta ../uploads/site si existe
  // ======================
  $uploadsSiteDir = BASE_DIR . '/uploads/site';

  if (is_dir($uploadsSiteDir)) {
    $it    = new RecursiveDirectoryIterator($uploadsSiteDir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($files as $file) {
      if ($file->isDir()) {
        rmdir($file->getRealPath());
      } else {
        unlink($file->getRealPath());
      }
    }
    rmdir($uploadsSiteDir);
  }

  // ======================
  // Copiar archivos de instalación a /uploads/site
  // ======================
  $sourceDir = BASE_DIR . '/install/images/site';
  $destDir   = BASE_DIR . '/uploads/site';

  if (!is_dir($destDir)) {
    if (!mkdir($destDir, 0755, true)) {
      throw new Exception("No se pudo crear la carpeta destino: $destDir");
    }
  }

  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
  );

  foreach ($it as $item) {
    $destPath = $destDir . DIRECTORY_SEPARATOR . $it->getSubPathName();
    if ($item->isDir()) {
      if (!is_dir($destPath)) {
        mkdir($destPath, 0755, true);
      }
    } else {
      if (!copy($item, $destPath)) {
        throw new Exception("No se pudo copiar el archivo: " . $item);
      }
    }
  }

  echo "<p style='color:green;'>✅ Logos y favicon actualizados correctamente.</p>";
  echo "<p><a href='update.php?step=1'>Volver al menú principal</a></p>";

} catch (Exception $e) {
  echo "<p style='color:red;'>⚠️ Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}
?>