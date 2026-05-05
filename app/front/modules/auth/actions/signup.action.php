<?php

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  header("Location: " . front_route());
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email     = clear_input($_POST['email'] ?? '');
  $username  = clear_input($_POST['username'] ?? '');
  $password  = $_POST['password'] ?? '';
  $password2 = $_POST['password_confirmation'] ?? '';

  // RATE LIMIT
  $rate = (new LoginRateLimiter($connect))->load();
  if ($rate->isBlocked()) {
    $notifier->message($rate->getBlockedMessage())->toast()->danger()->add();
    return;
  }

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

      $rate->failed();
      $notifier
        ->message("El correo o nombre de usuario ya están registrados")
        ->toast()
        ->danger()
        ->add();

    } else {

      // Hashing de contraseña (SEGURO)
      $hashed_password = $cipher->password($password);

      // Insertar usuario
      $query_insert = "
    INSERT INTO users (
          user_login,
          user_password,
          user_email,
          user_status,
          user_created
    ) VALUES (
          :username,
          :password,
          :email,
          1,
          NOW()
    )
      ";

      $stmt_insert = $connect->prepare($query_insert);
      $stmt_insert->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt_insert->bindParam(':password', $hashed_password, PDO::PARAM_STR);
      $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);

      if ($stmt_insert->execute()) {

        $new_user_id = $connect->lastInsertId();

        // Insertar rol por defecto (2 = Usuario) en usermeta
        $query_meta = "
          INSERT INTO usermeta (user_id, usermeta_key, usermeta_value) 
          VALUES (:uid, 'role_id', '2')
    ";
        $stmt_meta  = $connect->prepare($query_meta);
        $stmt_meta->execute([':uid' => $new_user_id]);
        
        $rate->success();

        $notifier

          ->message("Cuenta creada exitosamente. Ya puedes iniciar sesión.")
          ->toast()
          ->success()
          ->add();

        header("Location: " . front_route("signin"));
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