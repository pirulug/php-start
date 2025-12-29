<?php

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  header("Location: " . home_route());
  exit();
}

// --- AUTO LOGIN CON COOKIE ---
if (isset($_COOKIE['php-start'])) {
  try {
    // Intentar descifrar el user_id
    $user_id = $cipher->decrypt($_COOKIE['php-start']);

    // Validar que sea numérico (protección adicional)
    if (!is_numeric($user_id)) {
      throw new Exception("ID inválido en cookie");
    }

    $query = "SELECT * FROM users WHERE user_id = :user_id AND user_status = 1 AND role_id IN (1, 2)";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Actualizar última conexión
      $update = $connect->prepare("
        UPDATE users 
        SET user_last_login = NOW() 
        WHERE user_id = :user_id
      ");
      $update->bindParam(':user_id', $user->user_id, PDO::PARAM_INT);
      $update->execute();

      // Crear sesión
      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['signin']  = true;

      // Renovar cookie
      setcookie("php-start", $cipher->encrypt($user->user_id), time() + (30 * 24 * 60 * 60), "/");

      // Redirigir al perfil
      // $notifier->add("¡Bienvenido de nuevo, {$user->user_login}!", "success", "toast");
      $notifier->
        message("¡Bienvenido de nuevo, {$user->user_login}!")
        ->toast()
        ->success()
        ->add();

      header("Location: " . APP_URL . "/profile");
      exit();

    } else {
      // Usuario no existe o está inactivo => borrar cookie
      setcookie("php-start", "", time() - 3600, "/");
    }
  } catch (Exception $e) {
    // Error al descifrar => cookie corrupta o manipulada => eliminar
    setcookie("php-start", "", time() - 3600, "/");
  }
}

// --- LOGIN NORMAL ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sign_in'])) {
  $login       = clear_data($_POST['login'] ?? '');
  $password    = clear_data($_POST['password'] ?? '');
  $remember_me = isset($_POST['remember']);


  // Validar usuario
  if (empty($login)) {
    $notifier
      ->message("El campo usuario o correo es obligatorio")
      ->toast()
      ->danger()
      ->add();
  }

  // Validar contraseña
  if (empty($password)) {
    $notifier
      ->message("El campo contraseña es obligatorio")
      ->toast()
      ->danger()
      ->add();
  } else {
    $password = $cipher->encrypt($password);
  }

  // Si no hay errores, comprobar usuario y contraseña
  if (!$notifier->can()->danger()) {
    $query = "
      SELECT *
      FROM users
      WHERE (user_email = :email OR user_login = :user_login)
        AND user_password = :password
        AND user_status = 1
    ";

    $stmt = $connect->prepare($query);
    $stmt->bindParam(':email', $login, PDO::PARAM_STR);
    $stmt->bindParam(':user_login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Actualizar última conexión
      $update = $connect->prepare("
        UPDATE users 
        SET user_last_login = NOW() 
        WHERE user_id = :user_id
      ");
      $update->bindParam(':user_id', $user->user_id, PDO::PARAM_INT);
      $update->execute();

      // Crear sesión
      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['signin']  = true;

      // Recordar usuario
      if ($remember_me) {
        setcookie("php-start", $cipher->encrypt($user->user_id), time() + (30 * 24 * 60 * 60), "/");
      }

      $notifier->
        message("¡Bienvenido de nuevo, {$user->user_login}!")
        ->toast()
        ->success()
        ->add();

      header("Location: " . APP_URL . "/profile");
      exit();
    } else {
      $notifier->
        message("Correo o contraseña incorrectos")
        ->toast()
        ->danger()
        ->add();
    }
  }
}