<?php

/**
 * Acción para establecer la nueva contraseña a partir de un token.
 */

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  header("Location: " . home_route("account/profile"));
  exit();
}

$token = $args['token'] ?? '';

if (empty($token)) {
  $notifier->message("Token no proporcionado.")->danger()->toast()->add();
  header("Location: " . home_route("signin"));
  exit();
}

// 1. Validar Token en la Base de Datos
$stmt = $connect->prepare("
    SELECT u.user_id, u.user_login, um.usermeta_value as expiry
    FROM users u
    JOIN usermeta um ON u.user_id = um.user_id
    WHERE um.usermeta_key = 'reset_token' AND um.usermeta_value = :token
    AND u.user_status = 1
    LIMIT 1
");
$stmt->execute([':token' => $token]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

if (!$user) {
  $notifier->message("El enlace de recuperación es inválido.")->danger()->toast()->add();
  header("Location: " . home_route("signin"));
  exit();
}

// 2. Validar Expiración
$stmt_expiry = $connect->prepare("
    SELECT usermeta_value 
    FROM usermeta 
    WHERE user_id = :user_id AND usermeta_key = 'reset_token_expiry'
");
$stmt_expiry->execute([':user_id' => $user->user_id]);
$expiry_meta = $stmt_expiry->fetch(PDO::FETCH_OBJ);

if (!$expiry_meta || strtotime($expiry_meta->usermeta_value) < time()) {
  $notifier->message("El enlace de recuperación ha expirado.")->danger()->toast()->add();
  header("Location: " . home_route("signin"));
  exit();
}

// 3. Procesar Cambio de Contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $password         = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  if (strlen($password) < 6) {
    $notifier->message("La contraseña debe tener al menos 6 caracteres.")->danger()->toast()->add();
  } elseif ($password !== $confirm_password) {
    $notifier->message("Las contraseñas no coinciden.")->danger()->toast()->add();
  }

  if (!$notifier->can()->danger()) {
    
    // Hash de la nueva contraseña
    $hashed_password = $cipher->password($password);

    // Actualizar usuario
    $stmt_update = $connect->prepare("UPDATE users SET user_password = :password, user_updated = NOW() WHERE user_id = :user_id");
    
    if ($stmt_update->execute([':password' => $hashed_password, ':user_id' => $user->user_id])) {
      
      // Limpiar tokens de reset
      $stmt_clear = $connect->prepare("DELETE FROM usermeta WHERE user_id = :user_id AND usermeta_key IN ('reset_token', 'reset_token_expiry')");
      $stmt_clear->execute([':user_id' => $user->user_id]);

      // Invalidar tokens de "recordarme" antiguos por seguridad
      $stmt_clear_rem = $connect->prepare("DELETE FROM usermeta WHERE user_id = :user_id AND usermeta_key = 'remember_token'");
      $stmt_clear_rem->execute([':user_id' => $user->user_id]);

      $notifier->message("¡Tu contraseña ha sido actualizada! Ya puedes iniciar sesión.")->success()->toast()->add();
      
      header("Location: " . home_route("signin"));
      exit();

    } else {
      $notifier->message("Error al actualizar la contraseña.")->danger()->toast()->add();
    }
  }
}
