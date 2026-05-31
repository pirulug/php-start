<?php

/**
 * Acción para procesar y demostrar el editor WYSIWYG Osamu.
 */

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Obtener y sanitizar el contenido del editor usando la función de seguridad del framework
  $raw_content = $_POST["editor_content"] ?? "";
  $sanitized_content = clear_textarea($raw_content);

  // -----------------------------------------------------------------------------
  // SECCIÓN: TRASLADAR IMÁGENES DE TEMP A IMAGES
  // -----------------------------------------------------------------------------
  $pattern = "/\/storage\/uploads\/temp\/([a-zA-Z0-9_\-\.]+)/";
  if (preg_match_all($pattern, $sanitized_content, $matches)) {
    $temp_dir = BASE_DIR . "/storage/uploads/temp/";
    $dest_dir = BASE_DIR . "/storage/uploads/images/";

    if (!is_dir($dest_dir)) {
      mkdir($dest_dir, 0755, true);
    }

    foreach ($matches[1] as $filename) {
      $temp_file = $temp_dir . $filename;
      $dest_file = $dest_dir . $filename;

      if (file_exists($temp_file)) {
        rename($temp_file, $dest_file);
      }
    }

    $sanitized_content = str_replace("/storage/uploads/temp/", "/storage/uploads/images/", $sanitized_content);
  }

  // Almacenar temporalmente en la sesión para mostrar el resultado en la vista
  $_SESSION["osamu_demo_content"] = $sanitized_content;

  // Notificar al usuario con SweetAlert
  $notifier
    ->message("Contenido guardado y sanitizado correctamente.")
    ->sweetalert()
    ->success()
    ->add();

  // Redireccionar de vuelta para evitar reenvío de formulario
  header("Location: " . admin_route("components/osamu"));
  exit();
}
