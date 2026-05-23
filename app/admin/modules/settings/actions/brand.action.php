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

// Obtener favicon (ya viene decodificado como objeto por SiteConfig)
$st_favicon = (array) ($config->favicon ?? []);

$updateOption = function ($key, $newValue) use ($config, $uploadPathLogo) {
  // Capturar valor antiguo ANTES del upsert (porque el upsert refresca la caché)
  $oldValue = $config->get($key, '');

  meta_options_upsert($key, $newValue);

  // Eliminar anterior si existe y es diferente al nuevo
  if (!empty($oldValue) && $oldValue !== $newValue) {
    if (file_exists($uploadPathLogo . $oldValue)) {
      @unlink($uploadPathLogo . $oldValue);
    }
  }
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  try {
    // 1. LOGO OSCURO
    if (!empty($_FILES['st_darklogo']) && $_FILES['st_darklogo']['size'] > 0 && clear_image($_FILES['st_darklogo'])) {
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
    if (!empty($_FILES['st_whitelogo']) && $_FILES['st_whitelogo']['size'] > 0 && clear_image($_FILES['st_whitelogo'])) {
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
    if (!empty($_FILES['st_og_image']) && $_FILES['st_og_image']['size'] > 0 && clear_image($_FILES['st_og_image'])) {
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
    if (!empty($_FILES['st_favicon']) && $_FILES['st_favicon']['size'] > 0 && clear_image($_FILES['st_favicon'])) {
      $upFavicon = $_FILES['st_favicon'];
      if (mime_content_type($upFavicon['tmp_name']) !== 'image/png') {
        throw new Exception("El favicon debe ser una imagen PNG.");
      }

      $generator      = new FaviconGenerator($uploadPathFavicon);
      $generatedFiles = $generator->generate($upFavicon['tmp_name']);

      if (!empty($generatedFiles)) {
        // Eliminar antiguos favicons (ya viene decodificado)
        $oldFiles = (array) ($config->favicon ?? []);
        foreach ($oldFiles as $filename) {
          if (file_exists($uploadPathFavicon . $filename)) {
            @unlink($uploadPathFavicon . $filename);
          }
        }

        $newFavValue = json_encode($generatedFiles, JSON_UNESCAPED_SLASHES);
        meta_options_upsert('favicon', $newFavValue);

        $notifier->message("Favicon y manifiestos actualizados.")->success()->bootstrap()->add();
      }
    }

    // 5. LOGO TYPE & ICON SETTINGS
    if (isset($_POST['st_logo_type'])) {
      $logoType = clear_input($_POST['st_logo_type']);
      $updateOption('logo_type', $logoType);

      if (isset($_POST['st_logo_icon_source'])) {
        $updateOption('logo_icon_source', clear_input($_POST['st_logo_icon_source']));
      }

      if (isset($_POST['st_logo_icon_color'])) {
        $updateOption('logo_icon_color', clear_input($_POST['st_logo_icon_color']));
      }

      if (isset($_POST['st_logo_icon_class'])) {
        $updateOption('logo_icon_class', clear_input($_POST['st_logo_icon_class']));
      }

      if (isset($_POST['st_logo_icon_svg'])) {
        $svg_content = $_POST['st_logo_icon_svg'];
        if (stripos($svg_content, '<script') !== false || stripos($svg_content, 'javascript:') !== false) {
          throw new Exception("El código SVG contiene elementos no permitidos por seguridad.");
        }
        $updateOption('logo_icon_svg', $svg_content);
      }

      if (!empty($_FILES['st_logo_icon_file']) && $_FILES['st_logo_icon_file']['size'] > 0) {
        if (clear_image($_FILES['st_logo_icon_file'])) {
          $file = $_FILES['st_logo_icon_file'];
          $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
          $filename = 'logo_icon_' . time() . '.' . $ext;
          if (move_uploaded_file($file['tmp_name'], $uploadPathLogo . $filename)) {
            $updateOption('logo_icon_file', $filename);
          } else {
            throw new Exception("Error al mover el archivo del icono.");
          }
        } else {
          throw new Exception("El archivo del icono no es una imagen o SVG válido.");
        }
      }

      $notifier->message("Estilo e icono de logo actualizados.")->success()->bootstrap()->add();
    }

    // El refresco ya lo hace el helper meta_options_upsert

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();

  } catch (Exception $e) {
    $notifier->message($e->getMessage())->danger()->bootstrap()->add();
  }
}