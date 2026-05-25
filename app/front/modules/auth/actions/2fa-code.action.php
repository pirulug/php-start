<?php

// -----------------------------------------------------------------------------
// SECCIÓN: VERIFICACIÓN DE CÓDIGO 2FA (CODE) - FRONT
// -----------------------------------------------------------------------------

if (!isset($_SESSION['2fa_user_id'])) {
  $notifier->message("Acceso no autorizado. Por favor inicia sesión.")
    ->toast()
    ->danger()
    ->add();
  header("Location: " . front_route("signin"));
  exit();
}

$user_id = $_SESSION['2fa_user_id'];

// Obtener el secreto de 2FA del usuario
$stmtSecret = $connect->prepare("
  SELECT usermeta_value 
  FROM usermeta 
  WHERE user_id = :user_id 
    AND usermeta_key = '2fa_secret' 
    AND usermeta_value IS NOT NULL
    AND usermeta_value != ''
  LIMIT 1
");
$stmtSecret->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtSecret->execute();
$meta = $stmtSecret->fetch(PDO::FETCH_OBJ);

if (!$meta || empty($meta->usermeta_value)) {
  // Si no tiene secreto guardado válido, limpiar estado temporal y redirigir a login
  unset($_SESSION['2fa_user_id']);
  $notifier->message("Error de seguridad 2FA. Por favor inicia sesión de nuevo.")
    ->toast()
    ->danger()
    ->add();
  header("Location: " . front_route("signin"));
  exit();
}

$secret = $meta->usermeta_value;

// Obtener datos del usuario
$stmtUser = $connect->prepare("SELECT * FROM users WHERE user_id = :user_id LIMIT 1");
$stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtUser->execute();
$user = $stmtUser->fetch(PDO::FETCH_OBJ);

if (!$user) {
  unset($_SESSION['2fa_user_id']);
  $notifier->message("Usuario no encontrado. Por favor inicia sesión de nuevo.")
    ->toast()
    ->danger()
    ->add();
  header("Location: " . front_route("signin"));
  exit();
}

$ga = new GoogleAuthenticator();

// Procesar el envío del formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = clear_input($_POST['2fa_code'] ?? '');

  if (strlen($code) === 6 && $ga->verifyCode($secret, $code)) {
    // Código correcto: Iniciar sesión de forma oficial
    $_SESSION['user_id'] = $user->user_id;
    $_SESSION['signin']  = true;

    // Procesar "Remember Me" si estaba marcado
    if (!empty($_SESSION['2fa_remember'])) {
      $token      = bin2hex(random_bytes(32));
      $tokenHash  = hash('sha256', $token);
      $cookieData = $user->user_id . ':' . $token;

      // UPSERT usermeta para token de sesión persistente
      $stmtCookie = $connect->prepare("
        INSERT INTO usermeta (user_id, usermeta_key, usermeta_value)
        VALUES (:user_id, 'remember_token', :value)
        ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)
      ");
      $stmtCookie->bindParam(':user_id', $user->user_id, PDO::PARAM_INT);
      $stmtCookie->bindParam(':value', $tokenHash, PDO::PARAM_STR);
      $stmtCookie->execute();

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

    // Actualizar última fecha de login
    $stmtUpdate = $connect->prepare("
      UPDATE users SET user_last_login = NOW() WHERE user_id = :user_id
    ");
    $stmtUpdate->bindParam(':user_id', $user->user_id, PDO::PARAM_INT);
    $stmtUpdate->execute();

    // Notificación de éxito
    $notifier->message("¡Bienvenido de nuevo, {$user->user_login}!")
      ->toast()
      ->success()
      ->add();

    // Registrar en los logs
    $log->info("Usuario {$user->user_login} inició sesión correctamente tras verificar 2FA en front")
      ->file("home")
      ->with("user_id", $user->user_id)
      ->with("user_login", $user->user_login)
      ->write();

    // Limpiar variables temporales de la sesión
    unset($_SESSION['2fa_user_id']);
    unset($_SESSION['2fa_remember']);

    // Redirigir a URL original o a la vista de perfil de cuenta
    if (!empty($_SESSION['redirect_after_login'])) {
      $redirect = $_SESSION['redirect_after_login'];
      unset($_SESSION['redirect_after_login']);
      header("Location: " . $redirect);
      exit();
    }

    header("Location: " . front_route("account/profile"));
    exit();
  } else {
    // Código inválido
    $notifier->message("El código ingresado es incorrecto o ha expirado. Por favor, intenta de nuevo.")
      ->toast()
      ->danger()
      ->add();
  }
}
