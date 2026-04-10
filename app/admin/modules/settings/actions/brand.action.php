<?php

if (!extension_loaded("imagick")) {
  $notifier
    ->message("Imagick no esta instalado. El rendimiento y la generacion de favicons podrian verse afectados.")
    ->warning()
    ->bootstrap()
    ->add();
}

// Configuracion de rutas
$uploadPathLogo    = BASE_DIR . '/storage/uploads/site/';
$uploadPathFavicon = BASE_DIR . '/storage/uploads/site/favicons/';

// Obtener datos actuales usando FETCH_OBJ (Obligatorio)
$query = "SELECT option_key, option_value FROM options WHERE option_key IN ('favicon', 'white_logo', 'dark_logo', 'og_image')";
$stmt  = $connect->prepare($query);
$stmt->execute();
$rowsRaw = $stmt->fetchAll(PDO::FETCH_OBJ);

// Mapear a objeto de configuracion
$options = new stdClass();
foreach ($rowsRaw as $row) {
  $options->{$row->option_key} = $row->option_value;
}

// Decodificar favicon para la vista
$st_favicon = json_decode($options->favicon ?? '{}', true);

/**
 * Función auxiliar para actualizar una opción en BD y eliminar archivo anterior
 */
$updateOption = function($key, $newValue) use ($connect, $options, $uploadPathLogo) {
  $stmt = $connect->prepare("UPDATE options SET option_value = :val1 WHERE option_key = :key1");
  
  // Asignación a variables para bindParam
  $valToBind = $newValue;
  $keyToBind = $key;
  
  $stmt->bindParam(':val1', $valToBind);
  $stmt->bindParam(':key1', $keyToBind);
  $stmt->execute();

  // Eliminar anterior si existe y no es el mismo
  $oldValue = $options->{$key} ?? '';
  if (!empty($oldValue) && $oldValue !== $newValue) {
    if (file_exists($uploadPathLogo . $oldValue)) {
      @unlink($uploadPathLogo . $oldValue);
    }
  }
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  try {
    // 1. LOGO OSCURO
    if (!empty($_FILES['st_darklogo']) && $_FILES['st_darklogo']['size'] > 0) {
      $darklogo = (new UploadImage())
        ->file($_FILES['st_darklogo'])
        ->dir($uploadPathLogo)
        ->convertTo("webp")
        ->width(320)
        ->height(71)
        ->prefix("st_logo_dark_")
        ->upload();

      if ($darklogo['success']) {
        $updateOption('dark_logo', $darklogo['file_name']);
        $notifier->message("Logo oscuro actualizado.")->success()->bootstrap()->add();
      } else {
        throw new Exception("Error en Logo Oscuro: " . $darklogo['message']);
      }
    }

    // 2. LOGO CLARO
    if (!empty($_FILES['st_whitelogo']) && $_FILES['st_whitelogo']['size'] > 0) {
      $whitelogo = (new UploadImage())
        ->file($_FILES['st_whitelogo'])
        ->dir($uploadPathLogo)
        ->convertTo("webp")
        ->width(320)
        ->height(71)
        ->prefix("st_logo_light_")
        ->upload();

      if ($whitelogo['success']) {
        $updateOption('white_logo', $whitelogo['file_name']);
        $notifier->message("Logo claro actualizado.")->success()->bootstrap()->add();
      } else {
        throw new Exception("Error en Logo Claro: " . $whitelogo['message']);
      }
    }

    // 3. IMAGEN OPEN GRAPH
    if (!empty($_FILES['st_og_image']) && $_FILES['st_og_image']['size'] > 0) {
      $ogImage = (new UploadImage())
        ->file($_FILES['st_og_image'])
        ->dir($uploadPathLogo)
        ->convertTo("webp")
        ->width(1200)
        ->height(630)
        ->prefix("og_image_")
        ->upload();

      if ($ogImage['success']) {
        $updateOption('og_image', $ogImage['file_name']);
        $notifier->message("Imagen Social (OG) actualizada.")->success()->bootstrap()->add();
      } else {
        throw new Exception("Error en Imagen OG: " . $ogImage['message']);
      }
    }

    // 4. FAVICON GENERATOR
    if (!empty($_FILES['st_favicon']) && $_FILES['st_favicon']['size'] > 0) {
      $upFavicon = $_FILES['st_favicon'];
      if (mime_content_type($upFavicon['tmp_name']) !== 'image/png') {
        throw new Exception("El favicon debe ser una imagen PNG.");
      }

      $generator      = new FaviconGenerator($uploadPathFavicon);
      $generatedFiles = $generator->generate($upFavicon['tmp_name']);

      if (!empty($generatedFiles)) {
        // Eliminar antiguos favicons
        $oldFiles = json_decode($options->favicon ?? '{}', true);
        foreach ($oldFiles as $filename) {
          if (file_exists($uploadPathFavicon . $filename)) {
            @unlink($uploadPathFavicon . $filename);
          }
        }

        $newFavValue = json_encode($generatedFiles, JSON_UNESCAPED_SLASHES);
        $stmt = $connect->prepare("UPDATE options SET option_value = :valF WHERE option_key = :keyF");
        
        $keyToBind = 'favicon';
        $stmt->bindParam(':valF', $newFavValue);
        $stmt->bindParam(':keyF', $keyToBind);
        $stmt->execute();

        $notifier->message("Favicon y manifiestos actualizados.")->success()->bootstrap()->add();
      }
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;

  } catch (Exception $e) {
    $notifier->message($e->getMessage())->danger()->bootstrap()->add();
  }
}
