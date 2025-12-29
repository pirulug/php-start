<?php

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  header("Location: " . admin_route("dashboard"));
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

    // $query = "SELECT * FROM users WHERE user_id = :user_id AND user_status = 1 AND role_id IN (1, 2)";
    $query = "SELECT * FROM users WHERE user_id = :user_id AND user_status = 1";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Verificar si puede loguearse
      if (!can_user_login($connect, $user->user_id)) {

        $notifier->message("No tienes permisos para acceder al sistema.")
          ->bootstrap()
          ->danger()
          ->add();

        return;
      }

      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['signin']  = true;

      // Actualizar en la base de datos en last login
      $query = "
          UPDATE users 
          SET 
            user_last_login = NOW()
          WHERE 
            user_id = :user_id";
      $stmt  = $connect->prepare($query);
      $stmt->bindParam(":user_id", $user->user_id);
      $stmt->execute();

      // Redirigir a la URL original si existe
      if (!empty($_SESSION['redirect_after_login'])) {

        $redirect = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);

        header("Location: " . $redirect);
        exit;
      }

      header("Location: " . admin_route("dashboard"));
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_login    = clear_data($_POST['user-name']);
  $user_password = clear_data($_POST['user-password']);
  $remember_me   = $_POST['remember-me'];

  // =========================================================
  // ACCESS CONTROL (rate limit)
  // =========================================================
  $rate = (new LoginRateLimiter($connect))
    ->fromPost($user_login)
    ->resolveUser()
    ->load();

  if ($rate->isBlocked()) {
    $notifier->message($rate->getBlockedMessage())
      ->bootstrap()
      ->danger()
      ->add();

    return;
  }

  // Validar usuario
  if (empty($user_login)) {
    $notifier
      ->message("El campo usuario es obligatorio")
      ->bootstrap()
      ->danger()
      ->add();
  }

  // Validar contraseña
  if (empty($user_password)) {
    $notifier->message("El campo contraseña es obligatorio")
      ->bootstrap()
      ->danger()
      ->add();
  } else {
    $user_password = $cipher->encrypt($user_password);
  }

  // Recordar usuario
  // $remember_me = ($remember_me === 'remember-me') ? true : false;
  $remember_me = isset($_POST['remember-me']);

  // Si no hay errores, comprobar usuario y contraseña
  if (!$notifier->can()->danger()) {
    $query = "SELECT * FROM users WHERE user_login = :user_login AND user_password = :user_password";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_login', $user_login, PDO::PARAM_STR);
    $stmt->bindParam(':user_password', $user_password, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Verificar si puede loguearse
      if (!can_user_login($connect, $user->user_id)) {

        $notifier->message("No tienes permisos para acceder al sistema.")
          ->bootstrap()
          ->danger()
          ->add();

        return;
      }

      // Crear sesión
      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['signin']  = true;

      // Recordar usuario
      if ($remember_me) {
        setcookie(
          'php-start',
          $cipher->encrypt($user->user_id),
          [
            'expires'  => time() + (30 * 24 * 60 * 60),
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
          ]
        );
      }

      // Actualizar en la base de datos en last login
      $query = "
          UPDATE users 
          SET 
            user_last_login = NOW()
          WHERE 
            user_id = :user_id";
      $stmt  = $connect->prepare($query);
      $stmt->bindParam(":user_id", $user->user_id);
      $stmt->execute();

      // =========================================================
      // ACCESS CONTROL → login exitoso
      // =========================================================
      $rate->success();

      // Notificación de bienvenida
      $notifier->message("¡Bienvenido de nuevo, {$user->user_login}!")
        ->bootstrap()
        ->success()
        ->add();

      // Log de acceso
      $log->message("Usuario {$user->user_login} ha iniciado sesión")
        ->type("success")
        ->with("user_id", $user->user_id)
        ->with("user_login", $user->user_login)
        ->write();

      // Redirigir a la URL original si existe
      if (!empty($_SESSION['redirect_after_login'])) {

        $redirect = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);

        header("Location: " . $redirect);
        exit;
      }

      // Redirigir al dashboard
      header("Location: " . admin_route("dashboard"));
      exit();
    } else {
      // =========================================================
      // ACCESS CONTROL → fallo (IP + login)
      // =========================================================
      $rate->failed();

      if ($rate->isBruteForce()) {
        $rate->blockIpPermanently();
      }

      $notifier->message("Usuario o contraseña incorrectos")
        ->bootstrap()
        ->danger()
        ->add();
    }
  }
}