<?php

// Obtener ID
$id_user = $_SESSION["user_id"];

// Obtener user
$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

// Formulario POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {

  $currentPassword = trim($_POST['current_password'] ?? '');
  $newPassword     = trim($_POST['password'] ?? '');
  $confirmPassword = trim($_POST['confirm_password'] ?? '');
  $userId          = (int) $_SESSION['user_id'];

  // Validar contraseña actual
  if ($currentPassword === '') {
    $notifier->message("El campo 'Contraseña actual' no puede estar vacío.")->danger()->bootstrap()->add();
  } elseif (!$cipher->verifyPassword($currentPassword, $user->user_password)) {
    $notifier->message("La contraseña actual es incorrecta.")->danger()->bootstrap()->add();
  }

  // Validar nueva contraseña
  if ($newPassword === '' || $confirmPassword === '') {
    $notifier->message("La nueva contraseña y la confirmación no pueden estar vacías.")->danger()->bootstrap()->add();
  } elseif (strlen($newPassword) < 6) {
    $notifier->message("La nueva contraseña debe tener al menos 6 caracteres.")->danger()->bootstrap()->add();
  } elseif ($newPassword !== $confirmPassword) {
    $notifier->message("La nueva contraseña y la confirmación no coinciden.")->danger()->bootstrap()->add();
  }

  // Actualizar contraseña
  if (!$notifier->can()->danger()) {
    try {
      $connect->beginTransaction();

      $newHashedPassword = $cipher->password($newPassword);

      $sql  = "UPDATE users SET user_password = :password WHERE user_id = :user_id";
      $stmt = $connect->prepare($sql);
      $stmt->bindParam(':password', $newHashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->execute();

      // Deactivate remember token
      $sql_meta = "UPDATE usermeta SET usermeta_value = NULL WHERE user_id = :user_id AND usermeta_key = 'remember_token'";
      $stmt_meta = $connect->prepare($sql_meta);
      $stmt_meta->execute([':user_id' => $userId]);

      $connect->commit();

      $notifier->message("La contraseña se ha actualizado correctamente.")->success()->bootstrap()->add();
      header("Location: " . admin_route("account/settings/password"));
      exit();
    } catch (Exception $e) {
      $connect->rollBack();
      $notifier->message("Error: " . $e->getMessage())->danger()->bootstrap()->add();
    }
  }
}
