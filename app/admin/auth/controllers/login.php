<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_name     = clear_data($_POST['user-name']);
  $user_password = clear_data($_POST['user-password']);
  $remember_me   = $_POST['remember-me'];

  // Validar usuario
  if (empty($user_name)) {
    $notifier->add("El campo usuario es obligatorio", "danger");
  }

  // Validar contraseña
  if (empty($user_password)) {
    $notifier->add("El campo contraseña es obligatorio", "danger");
  } else {
    $user_password = $cipher->encrypt($user_password);
  }

  // Recordar usuario
  $remember_me = ($remember_me === 'remember-me') ? true : false;

  // Si no hay errores, comprobar usuario y contraseña
  if (!$notifier->hasErrors()) {
    $query = "SELECT * FROM users WHERE user_name = :user_name AND user_password = :user_password AND user_status = 1 AND role_id IN (1, 2)";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
    $stmt->bindParam(':user_password', $user_password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Crear sesión
      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['signin']  = true;

      // Recordar usuario
      if ($remember_me) {
        setcookie("user_id", $user->user_id, time() + (30 * 24 * 60 * 60), "/");
      }

      $notifier->add("¡Bienvenido de nuevo, {$user->user_name}!", "success");
      // Redirigir al dashboard
      header("Location: " . url_admin("dashboard"));
      exit();
    } else {
      $notifier->add("Usuario o contraseña incorrectos", "danger");
    }
  }
}