<?php

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  header("Location: " . home_route());
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email     = clear_data($_POST['email'] ?? '');
  $username  = clear_data($_POST['username'] ?? '');
  $password  = clear_data($_POST['password'] ?? '');
  $password2 = clear_data($_POST['password_confirmation'] ?? '');

  /*
  |--------------------------------------------------------------------------
  | Validaciones básicas
  |--------------------------------------------------------------------------
  */
  if (empty($email) || empty($username) || empty($password) || empty($password2)) {
    $notifier
      ->message("Todos los campos son obligatorios")
      ->toast()
      ->danger()
      ->add();
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $notifier
      ->message("El correo electrónico no es válido")
      ->toast()
      ->danger()
      ->add();
  } elseif ($password !== $password2) {
    $notifier
      ->message("Las contraseñas no coinciden")
      ->toast()
      ->danger()
      ->add();
  }

  /*
  |--------------------------------------------------------------------------
  | Si no hay errores, continuar
  |--------------------------------------------------------------------------
  */
  if (!$notifier->can()->danger()) {

    // Verificar duplicados
    $query_check = "
      SELECT user_id 
      FROM users 
      WHERE user_email = :email 
         OR user_login = :username
      LIMIT 1
    ";

    $stmt_check = $connect->prepare($query_check);
    $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt_check->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {

      $notifier
        ->message("El correo o nombre de usuario ya están registrados")
        ->toast()
        ->danger()
        ->add();

    } else {

      // Encriptar contraseña
      $encrypted_password = $cipher->encrypt($password);

      // Insertar usuario
      $query_insert = "
        INSERT INTO users (
          user_login,
          user_password,
          user_email,
          user_status,
          role_id,
          user_created
        ) VALUES (
          :username,
          :password,
          :email,
          1,
          2,
          NOW()
        )
      ";

      $stmt_insert = $connect->prepare($query_insert);
      $stmt_insert->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt_insert->bindParam(':password', $encrypted_password, PDO::PARAM_STR);
      $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);

      if ($stmt_insert->execute()) {

        $notifier
          ->message("Cuenta creada exitosamente. Ya puedes iniciar sesión.")
          ->toast()
          ->success()
          ->add();

        header("Location: " . APP_URL . "/signin");
        exit();

      } else {

        $notifier
          ->message("Error al registrar el usuario. Inténtalo nuevamente.")
          ->toast()
          ->danger()
          ->add();
      }
    }
  }
}
