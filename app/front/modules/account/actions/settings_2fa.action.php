<?php

// -----------------------------------------------------------------------------
// SECCIÓN: LOGICA DE AJUSTES 2FA (FRONT)
// -----------------------------------------------------------------------------

$id_user = $_SESSION["user_id"];

// Obtener datos del usuario
$query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
$stmt  = $connect->prepare($query);
$stmt->bindParam(":user_id", $id_user, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_OBJ);

if (!$user) {
  header("Location: " . front_route("signout"));
  exit();
}

$ga = new GoogleAuthenticator();

// Consultar si tiene 2FA activo en usermeta (debe tener un valor no nulo y no vacío)
$stmtSecret = $connect->prepare("
  SELECT usermeta_value 
  FROM usermeta 
  WHERE user_id = :user_id 
    AND usermeta_key = '2fa_secret' 
    AND usermeta_value IS NOT NULL
    AND usermeta_value != ''
  LIMIT 1
");
$stmtSecret->bindParam(':user_id', $id_user, PDO::PARAM_INT);
$stmtSecret->execute();
$meta = $stmtSecret->fetch(PDO::FETCH_OBJ);

$is_2fa_enabled = ($stmtSecret->rowCount() === 1);
$secret = '';

if ($is_2fa_enabled) {
  $secret = $meta->usermeta_value;

  // PROCESAR DESACTIVACIÓN DE 2FA
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disable_2fa'])) {
    $current_password = trim($_POST['current_password'] ?? '');

    // Validar contraseña
    if ($current_password === '') {
      $notifier->message("Debes ingresar tu contraseña para desactivar el 2FA.")
        ->toast()
        ->danger()
        ->add();
    } elseif (!$cipher->verifyPassword($current_password, $user->user_password)) {
      $notifier->message("La contraseña actual ingresada es incorrecta.")
        ->toast()
        ->danger()
        ->add();
    }

    if (!$notifier->can()->danger()) {
      // Vaciar el 2FA secret de usermeta en lugar de eliminar el registro
      $stmtUpdateMeta = $connect->prepare("
        UPDATE usermeta 
        SET usermeta_value = '' 
        WHERE user_id = :user_id 
          AND usermeta_key = '2fa_secret'
      ");
      $stmtUpdateMeta->bindParam(':user_id', $id_user, PDO::PARAM_INT);
      $stmtUpdateMeta->execute();

      // Guardar log
      $log->info("Usuario {$user->user_login} desactivó la autenticación en dos factores (2FA) desde front")
        ->file("home")
        ->with("user_id", $user->user_id)
        ->write();

      $notifier->message("La autenticación de doble factor (2FA) ha sido desactivada correctamente.")
        ->toast()
        ->success()
        ->add();

      header("Location: " . front_route("account/settings/2fa"));
      exit();
    }
  }
} else {
  // PROCESAR ACTIVACIÓN DE 2FA
  if (!isset($_SESSION['2fa_settings_temp_secret'])) {
    $_SESSION['2fa_settings_temp_secret'] = $ga->createSecret();
  }

  $temp_secret = $_SESSION['2fa_settings_temp_secret'];
  $qr_code_url = $ga->getQrCodeUrl($user->user_login, $temp_secret, $config->siteName());

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enable_2fa'])) {
    $code = clear_input($_POST['2fa_code'] ?? '');

    if (strlen($code) === 6 && $ga->verifyCode($temp_secret, $code)) {
      // Código correcto: guardar el secreto de forma permanente en usermeta
      $stmtSave = $connect->prepare("
        INSERT INTO usermeta (user_id, usermeta_key, usermeta_value)
        VALUES (:user_id, '2fa_secret', :secret)
        ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)
      ");
      $stmtSave->bindParam(':user_id', $id_user, PDO::PARAM_INT);
      $stmtSave->bindParam(':secret', $temp_secret, PDO::PARAM_STR);
      $stmtSave->execute();

      // Guardar log
      $log->info("Usuario {$user->user_login} activó la autenticación en dos factores (2FA) desde front")
        ->file("home")
        ->with("user_id", $user->user_id)
        ->write();

      $notifier->message("¡La autenticación de doble factor (2FA) ha sido activada y configurada con éxito!")
        ->toast()
        ->success()
        ->add();

      // Limpiar secreto temporal
      unset($_SESSION['2fa_settings_temp_secret']);

      header("Location: " . front_route("account/settings/2fa"));
      exit();
    } else {
      $notifier->message("El código ingresado es incorrecto o ha expirado. Por favor, intenta de nuevo.")
        ->toast()
        ->danger()
        ->add();
    }
  }
}
