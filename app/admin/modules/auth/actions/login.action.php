<?php

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  header("Location: " . admin_route("dashboard"));
  exit();
}

// AUTO LOGIN CON COOKIE 
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

// LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_login    = trim($_POST['user-name'] ?? '');
  $user_password = trim($_POST['user-password'] ?? '');
  $remember_me   = isset($_POST['remember-me']);

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

  // VALIDACIONES
  if ($user_login === '') {
    $notifier->message("El campo usuario es obligatorio")
      ->bootstrap()
      ->danger()
      ->add();
  }

  if ($user_password === '') {
    $notifier->message("El campo contraseña es obligatorio")
      ->bootstrap()
      ->danger()
      ->add();
  }

  // PROCESO LOGIN
  if (!$notifier->can()->danger()) {

    $query = "SELECT * FROM users 
              WHERE user_login = :user_login 
              AND user_status = 1
              LIMIT 1";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_login', $user_login, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {

      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Verificar contraseña (CLAVE)
      if (!$cipher->verifyPassword($user_password, $user->user_password)) {

        $rate->failed();

        $notifier->message("Usuario o contraseña incorrectos")
          ->bootstrap()
          ->danger()
          ->add();
        return;
      }

      if (!can_user_login($connect, $user->user_id)) {
        $notifier->message("No tienes permisos para acceder al sistema.")
          ->bootstrap()
          ->danger()
          ->add();
        return;
      }

      // LOGIN OK
      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['signin']  = true;

      if ($remember_me) {
        setcookie(
          'php-start',
          $cipher->encrypt((string) $user->user_id),
          [
            'expires'  => time() + (30 * 24 * 60 * 60),
            'path'     => '/',
            'secure'   => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
          ]
        );
      }

      $stmt = $connect->prepare(
        "UPDATE users SET user_last_login = NOW() WHERE user_id = :user_id"
      );
      $stmt->bindParam(':user_id', $user->user_id);
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

      // log
      $log->info("Usuario {$user->user_login} ha iniciado sesión")
        ->file("dashboard")
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

      header("Location: " . admin_route("dashboard"));
      exit;

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
