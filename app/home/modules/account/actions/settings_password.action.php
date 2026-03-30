<?php

/**
 * Vista de seguridad de la cuenta.
 */

// Formulario POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Cambio de contraseña
  if (isset($_POST['change_password'])) {

    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword     = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $userId          = (int) $_SESSION['user_id'];

    // Obtener contraseña actual desde la DB
    $sql  = "SELECT user_password FROM users WHERE user_id = :user_id LIMIT 1";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user) {
      $notifier
        ->message("Usuario no encontrado.")
        ->danger()
        ->toast()
        ->add();
    }

    // Validar contraseña actual
    if ($currentPassword === '') {
      $notifier
        ->message("El campo 'Contraseña actual' no puede estar vacío.")
        ->danger()
        ->toast()
        ->add();

    } elseif (!$cipher->verifyPassword($currentPassword, $user->user_password)) {
      $notifier
        ->message("La contraseña actual es incorrecta.")
        ->danger()
        ->toast()
        ->add();
    }

    // Validar nueva contraseña
    if ($newPassword === '' || $confirmPassword === '') {
      $notifier
        ->message("La nueva contraseña y la confirmación no pueden estar vacías.")
        ->danger()
        ->toast()
        ->add();

    } elseif (strlen($newPassword) < 6) {
      $notifier
        ->message("La nueva contraseña debe tener al menos 6 caracteres.")
        ->danger()
        ->toast()
        ->add();

    } elseif ($newPassword !== $confirmPassword) {
      $notifier
        ->message("La nueva contraseña y la confirmación no coinciden.")
        ->danger()
        ->toast()
        ->add();
    }

    // Actualizar contraseña
    if (!$notifier->can()->danger()) {

      $newHashedPassword = $cipher->password($newPassword);

      $sql  = "UPDATE users SET user_password = :password WHERE user_id = :user_id";
      $stmt = $connect->prepare($sql);
      $stmt->bindParam(':password', $newHashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

      if ($stmt->execute()) {

        $stmt = $connect->prepare("
          UPDATE usermeta
          SET usermeta_value = NULL
          WHERE user_id = :user_id
          AND usermeta_key = 'remember_token'
        ");
        $stmt->execute([
          ':user_id' => $userId
        ]);

        $notifier
          ->message("La contraseña se ha actualizado correctamente.")
          ->success()
          ->toast()
          ->add();

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();

      } else {
        $notifier
          ->message("Hubo un error al actualizar la contraseña.")
          ->danger()
          ->toast()
          ->add();
      }
    } else {
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
    }
  }
}
