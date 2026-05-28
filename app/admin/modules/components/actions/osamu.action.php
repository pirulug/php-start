<?php

/**
 * Acción para procesar y demostrar el editor WYSIWYG Osamu.
 */

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Obtener y sanitizar el contenido del editor usando la función de seguridad del framework
  $raw_content = $_POST["editor_content"] ?? "";
  $sanitized_content = clear_textarea($raw_content);

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
