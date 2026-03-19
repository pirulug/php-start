<?php

// 1. Obtener el ID del usuario desde los argumentos del Router
$user_id = $cipher->decrypt($args['id']) ?? null;

// Validar que el ID exista
if (!$user_id) {
  $notifier->message("ID de usuario no especificado.")->danger()->bootstrap()->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Proteger al Super Administrador principal (ID = 1)
$superAdmins = defined('SUPERADMIN_ID') ? SUPERADMIN_ID : [1];
if (in_array((int) $user_id, $superAdmins)) {
  $notifier
    ->message("El Super Administrador principal no puede ser desactivado por medidas de seguridad.")
    ->danger()
    ->bootstrap()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

// Evitar que el usuario se desactive a sí mismo (Prevenir auto-bloqueo)
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
  $notifier
    ->message("No puedes desactivar tu propia cuenta mientras estás en sesión.")
    ->warning()
    ->bootstrap()
    ->add();
  header("Location: " . admin_route("users"));
  exit();
}

try {
  // 2. Verificar si el usuario existe y obtener su estado actual
  $stmt_get = $connect->prepare("SELECT user_login, user_status FROM users WHERE user_id = :id LIMIT 1");
  $stmt_get->bindParam(':id', $user_id, PDO::PARAM_INT);
  $stmt_get->execute();
  $user = $stmt_get->fetch(PDO::FETCH_OBJ);

  if (!$user) {
    $notifier
      ->message("El usuario no existe o fue eliminado.")
      ->danger()
      ->bootstrap()
      ->add();
  } else {
    // 3. Alternar el estado (Toggle: Si es 1 pasa a 0, si es 0 pasa a 1)
    $new_status  = ($user->user_status == 1) ? 0 : 1;
    $status_text = ($new_status == 1) ? 'activado' : 'desactivado';

    $stmt_update = $connect->prepare("UPDATE users SET user_status = :status WHERE user_id = :id");
    $stmt_update->bindParam(':status', $new_status, PDO::PARAM_INT);
    $stmt_update->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
      // Usar 'warning' visualmente cuando se desactiva y 'success' cuando se activa
      if ($new_status == 1) {
        $notifier
          ->message("El usuario '{$user->user_login}' ha sido {$status_text} correctamente y ya puede ingresar al sistema.")
          ->success()
          ->bootstrap()
          ->add();
      } else {
        $notifier
          ->message("El usuario '{$user->user_login}' ha sido {$status_text}. Su acceso al sistema ha sido bloqueado.")
          ->warning()
          ->bootstrap()
          ->add();
      }
    } else {
      $notifier
        ->message("Hubo un error al cambiar el estado del usuario.")
        ->danger()
        ->bootstrap()
        ->add();
    }
  }
} catch (PDOException $e) {
  $notifier
    ->message("Error de Base de Datos: " . $e->getMessage())
    ->danger()
    ->bootstrap()
    ->add();
}

// Redirigir al listado de usuarios
header("Location: " . admin_route("users"));
exit();