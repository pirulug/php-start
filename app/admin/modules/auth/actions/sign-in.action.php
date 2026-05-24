<?php

if (is_admin()) {
  header("Location: " . admin_route("dashboard"));
  exit();
}

// -----------------------------------------------------------------------------
// SECCIÓN: AUTO LOGIN CON COOKIE
// -----------------------------------------------------------------------------
if (isset($_COOKIE[COOKIE_PREFIX . 'auth'])) {
  try {
    $data = $cipher->decrypt($_COOKIE[COOKIE_PREFIX . 'auth']);

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
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

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

    if (is_admin()) {
      $notifier
        ->success("¡Bienvenido de nuevo, {$user->user_nickname}!")
        ->toast()
        ->add();
      header("Location: " . admin_route("dashboard"));
      exit();
    } else {
      header("Location: " . front_route("account/profile"));
      exit();
    }

  } catch (Exception $e) {
    setcookie(COOKIE_PREFIX . 'auth', '', time() - 3600, '/');
  }
}

// -----------------------------------------------------------------------------
// SECCIÓN: PROCESAMIENTO DE LOGIN (POST)
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_login_raw = $_POST['user_login'] ?? '';
  $user_login     = clear_input($user_login_raw);
  $user_password  = $_POST['user_password'] ?? '';
  $remember_me    = isset($_POST['remember_me']);

  // Control de intentos (rate limit)
  $rate = (new LoginRateLimiter($connect))
    ?->fromPost($user_login_raw)
    ?->resolveUser()
    ?->load();

  if ($rate && $rate->isBlocked()) {
    $notifier->danger($rate->getBlockedMessage())
      ->bootstrap()
      ->add();
    return;
  }

  // Validaciones de campos
  if ($user_login === '') {
    $notifier->danger("El campo usuario es obligatorio")
      ->bootstrap()
      ->add();
  }

  if ($user_password === '') {
    $notifier->danger("El campo contraseña es obligatorio")
      ->bootstrap()
      ->add();
  }

  // Proceso de autenticación
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

      // Verificar contraseña
      if (!$cipher->verifyPassword($user_password, $user->user_password)) {

        if ($rate) {
          $rate->failed();
        }

        $notifier->danger("Usuario o contraseña incorrectos")
          ->bootstrap()
          ->add();
        return;
      }

      if (!can_user_access_admin($connect, $user->user_id)) {
        $notifier->danger("No tienes permisos para acceder al sistema.")
          ->bootstrap()
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
        $user_id_val = $user->user_id;
        $stmt->bindParam(':user_id', $user_id_val, PDO::PARAM_INT);
        $stmt->bindParam(':value', $tokenHash, PDO::PARAM_STR);
        $stmt->execute();

        // Cookie cifrada
        setcookie(
          COOKIE_PREFIX . 'auth',
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
      $user_id_val = $user->user_id;
      $stmt->bindParam(':user_id', $user_id_val, PDO::PARAM_INT);
      $stmt->execute();

      if ($rate) {
        $rate->success();
      }

      // Notificación de bienvenida
      $notifier->success("¡Bienvenido de nuevo, {$user->user_login}!")
        ->bootstrap()
        ->add();

      // Registro de log
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
        exit();
      }

      header("Location: " . admin_route("dashboard"));
      exit();

    } else {
      if ($rate) {
        $rate->failed();
        if ($rate->isBruteForce()) {
          $rate->blockIpPermanently();
        }
      }

      $notifier->danger("Usuario o contraseña incorrectos")
        ->bootstrap()
        ->add();
    }
  }
}
