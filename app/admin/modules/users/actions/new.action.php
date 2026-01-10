<?php

// Obtener roles para el select
$query = "SELECT role_id, role_name FROM roles ORDER BY role_name ASC";
$stmt  = $connect->prepare($query);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_OBJ);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener los datos del formulario y limpiarlos
  $user_login  = ($_POST['user_login']);
  $user_email  = ($_POST['user_email']);
  $role_id     = ($_POST['role_id']);
  $user_status = ($_POST['user_status']);
  $password    = ($_POST['user_password']);

  // Validar el nombre de usuario
  if (strlen($user_login) < 4) {
    $notifier
      ->message("El nombre de usuario debe tener al menos 4 caracteres.")
      ->danger()
      ->bootstrap()
      ->add();
  } else {
    try {
      $query     = "SELECT COUNT(*) AS count FROM users WHERE user_login = :user_login";
      $statement = $connect->prepare($query);
      $statement->bindParam(':user_login', $user_login);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        $notifier
          ->message("El nombre de usuario ya está en uso.")
          ->danger()
          ->bootstrap()
          ->add();
      }
    } catch (PDOException $e) {
      // $log->log('error', "Error en verificación de nombre de usuario", $e->getMessage());
    }
  }

  // Validar email
  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    $notifier
      ->message("El email ingresado no es válido.")
      ->danger()
      ->bootstrap()
      ->add();
  } else {
    try {
      $query     = "SELECT COUNT(*) AS count FROM users WHERE user_email = :user_email";
      $statement = $connect->prepare($query);
      $statement->bindParam(':user_email', $user_email);
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        $notifier
          ->message("El email ya está registrado.")
          ->danger()
          ->bootstrap()
          ->add();
      }
    } catch (PDOException $e) {
    }
  }

  // Validar contraseña
  if (strlen($password) < 6) {
    $notifier
      ->message("La contraseña debe tener al menos 6 caracteres.")
      ->danger()
      ->bootstrap()
      ->add();
  }

  // Validar rol y estatus
  if (empty($role_id) && $role_id !== '') {
    $notifier
      ->message("Seleccionar rol.")
      ->danger()
      ->bootstrap()
      ->add();
  }

  if (!in_array($user_status, [0, 1])) {
    $notifier
      ->message("Seleccionar estatus.")
      ->danger()
      ->bootstrap()
      ->add();
  }

  // Imagen
  if (!empty($_FILES['user_image']) && $_FILES['user_image']['size'] > 0) {
    if (!$notifier->can()->danger()) {

      $upload_path = BASE_DIR . '/uploads/user/';

      $user_image = (new UploadImage())
        ->file($_FILES['user_image'])
        ->dir($upload_path)
        ->convertTo("webp")
        ->width(100)
        ->height(100)
        ->upload();

      if (!$user_image['success']) {
        $notifier
          ->message($user_image['message'])
          ->danger()
          ->bootstrap()
          ->add();
      } else {
        $user_image = $user_image['file_name'];
      }
    } else {
      $user_image = "default.webp";
    }
  } else {
    $user_image = "default.webp";
  }

  // Si no hay errores, insertar
  if (!$notifier->can()->danger()) {
    $hashed_password = $cipher->password($password);

    try {
      $query     = "INSERT INTO users 
        (user_login, user_email, user_nickname, user_display_name, role_id, user_status, user_password, user_image, user_updated) 
        VALUES 
        (:user_login, :user_email, :user_nickname, :user_display_name, :role_id, :user_status, :user_password, :user_image, CURRENT_TIME)";
      $statement = $connect->prepare($query);

      $statement->bindParam(':user_login', $user_login);
      $statement->bindParam(':user_email', $user_email);
      $statement->bindParam(':user_nickname', $user_login);
      $statement->bindParam(':user_display_name', $user_login);
      $statement->bindParam(':role_id', $role_id);
      $statement->bindParam(':user_status', $user_status);
      $statement->bindParam(':user_password', $hashed_password);
      $statement->bindParam(':user_image', $user_image);

      if ($statement->execute()) {

        $notifier
          ->message("El nuevo usuario se insertó correctamente.")
          ->success()
          ->bootstrap()
          ->add();

        header("Location: " . admin_route("users"));
        exit();
      } else {
        $notifier
          ->message("Hubo un error al intentar insertar el nuevo usuario.")
          ->danger()
          ->bootstrap()
          ->add();
      }
    } catch (PDOException $e) {
      $notifier
        ->message("Error de base de datos: " . $e->getMessage())
        ->danger()
        ->bootstrap()
        ->add();
    }
  }
}

