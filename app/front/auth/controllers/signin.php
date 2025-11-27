<?php

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
      $notifier->add("¡Bienvenido de nuevo, {$user->user_login}!", "success", "toast");
      header("Location: " . SITE_URL . "/profile");
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
  $email       = clear_data($_POST['email'] ?? '');
  $password    = clear_data($_POST['password'] ?? '');
  $remember_me = isset($_POST['remember']) ? true : false;

  // Validar usuario
  if (empty($email)) {
    $notifier->add("El campo correo electrónico es obligatorio", "danger", "toast");
  }

  // Validar contraseña
  if (empty($password)) {
    $notifier->add("El campo contraseña es obligatorio", "danger", "toast");
  } else {
    // 🔐 Encriptar o hashear según tu sistema actual
    $password = $cipher->encrypt($password);
  }

  // Si no hay errores, comprobar usuario y contraseña
  if (!$notifier->hasErrors()) {
    $query = "SELECT * FROM users 
              WHERE user_email = :email 
              AND user_password = :password 
              AND user_status = 1";

    $stmt = $connect->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
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

      $notifier->add("¡Bienvenido de nuevo, {$user->user_login}!", "success", "toast");
      header("Location: " . SITE_URL . "/profile");
      exit();
    } else {
      $notifier->add("Correo o contraseña incorrectos", "danger", "toast");
    }
  }
}