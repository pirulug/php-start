<?php

if (isset($_SESSION["signin"]) && $_SESSION["signin"] === true) {
  header("Location: " . home_route("profile"));
  exit();
}

// AUTO LOGIN CON COOKIE 
if (isset($_COOKIE['php-start'])) {
  try {
    $data = $cipher->decrypt($_COOKIE['php-start']);

    if (!str_contains($data, ':')) {
      throw new Exception('Formato inválido');
    }

    [$user_id, $token] = explode(':', $data, 2);

    if (!is_numeric($user_id) || empty($token)) {
      throw new Exception('Datos inválidos');
    }

    $stmt = $connect->prepare("
      SELECT u.*, um.usermeta_value AS token_hash
      FROM users u
      LEFT JOIN usermeta um
        ON um.user_id = u.user_id
        AND um.usermeta_key = 'remember_token'
      WHERE u.user_id = :user_id
      AND u.user_status = 1
      LIMIT 1
    ");
    $stmt->execute([':user_id' => $user_id]);

    if (!$stmt->rowCount()) {
      throw new Exception('Usuario no válido');
    }

    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (
      empty($user->token_hash) ||
      !hash_equals($user->token_hash, hash('sha256', $token))
    ) {
      throw new Exception('Token inválido');
    }

    // LOGIN OK
    $_SESSION['user_id'] = $user->user_id;
    $_SESSION['signin']  = true;

    $notifier
      ->message("Bienbenido {$user->user_nickname}")
      ->success()
      ->toast()
      ->add();
    header("Location: " . home_route("profile"));
    exit();

  } catch (Exception $e) {
    setcookie('php-start', '', time() - 3600, '/');
  }
}

// LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_login    = clear_data($_POST['login']);
  $user_password = clear_data($_POST['password']);
  $remember_me   = isset($_POST['remember']);

  // =========================================================
  // ACCESS CONTROL (rate limit)
  // =========================================================
  $rate = (new LoginRateLimiter($connect))
    ->fromPost($user_login)
    ->resolveUser()
    ->load();

  if ($rate->isBlocked()) {
    $notifier->message($rate->getBlockedMessage())
      ->toast()
      ->danger()
      ->add();
    return;
  }

  // VALIDACIONES
  if ($user_login === '') {
    $notifier->message("El campo usuario es obligatorio")
      ->toast()
      ->danger()
      ->add();
  }

  if ($user_password === '') {
    $notifier->message("El campo contraseña es obligatorio")
      ->toast()
      ->danger()
      ->add();
  }

  // PROCESO LOGIN
  if (!$notifier->can()->danger()) {

    $query = "
      SELECT *
      FROM users
      WHERE (user_email = :user_email OR user_login = :user_login)
        AND user_status = 1
      LIMIT 1
    ";
    $stmt  = $connect->prepare($query);
    $stmt->bindParam(':user_email', $user_login);
    $stmt->bindParam(':user_login', $user_login);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {

      $user = $stmt->fetch(PDO::FETCH_OBJ);

      // Verificar contraseña (CLAVE)
      if (!$cipher->verifyPassword($user_password, $user->user_password)) {

        $rate->failed();

        $notifier->message("Usuario o contraseña incorrectos")
          ->toast()
          ->danger()
          ->add();
        return;
      }

      if (!can_user_login($connect, $user->user_id)) {
        $notifier->message("No tienes permisos para acceder al sistema.")
          ->toast()
          ->danger()
          ->add();
        return;
      }

      // LOGIN OK
      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['signin']  = true;

      if ($remember_me) {
        $token      = bin2hex(random_bytes(32));
        $tokenHash  = hash('sha256', $token);
        $cookieData = $user->user_id . ':' . $token;

        // UPSERT usermeta
        $stmt = $connect->prepare("
          INSERT INTO usermeta (user_id, usermeta_key, usermeta_value)
          VALUES (:user_id, 'remember_token', :value)
          ON DUPLICATE KEY UPDATE
            usermeta_value = VALUES(usermeta_value)
        ");
        $stmt->execute([
          ':user_id' => $user->user_id,
          ':value'   => $tokenHash
        ]);

        // Cookie cifrada
        setcookie(
          'php-start',
          $cipher->encrypt($cookieData),
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
        ->toast()
        ->success()
        ->add();

      // log
      $log->info("Usuario {$user->user_login} ha iniciado sesión")
        ->file("home")
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

      header("Location: " . home_route("profile"));
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
        ->toast()
        ->danger()
        ->add();
    }
  }
}
