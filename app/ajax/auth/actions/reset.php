<?php

/**
 * Acción AJAX para solicitar el restablecimiento de contraseña.
 */

// Validación del método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode([
    'success' => false,
    'message' => 'Método no permitido.'
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$email = trim($_POST['email'] ?? '');

// Validaciones básicas
if ($email === '') {
  echo json_encode([
    'success' => false,
    'message' => 'El correo electrónico es obligatorio.'
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode([
    'success' => false,
    'message' => 'El formato del correo no es válido.'
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

// Proceso de negocio
try {
  // Verificar si el usuario existe
  $stmt = $connect->prepare("SELECT user_id, user_login FROM users WHERE user_email = :email AND user_status = 1 LIMIT 1");
  $stmt->execute([':email' => $email]);
  $user = $stmt->fetch(PDO::FETCH_OBJ);

  if ($user) {
    // Generar token único
    $token  = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Guardar token en usermeta (UPSERT)
    $meta_data = [
      'reset_token'        => $token,
      'reset_token_expiry' => $expiry
    ];

    foreach ($meta_data as $key => $value) {
      $stmt_meta = $connect->prepare("
        INSERT INTO usermeta (user_id, usermeta_key, usermeta_value)
        VALUES (:user_id, :key, :value)
        ON DUPLICATE KEY UPDATE usermeta_value = VALUES(usermeta_value)
      ");
      $stmt_meta->execute([
        ':user_id' => $user->user_id,
        ':key'     => $key,
        ':value'   => $value
      ]);
    }

    // Preparar Correo
    $reset_link = APP_URL . "/reset-password/confirm/" . $token;
    $site_name  = $config->get("site_name");
    
    $subject = "Restablecer tu contraseña - {$site_name}";
    $body = "
      <div style='font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;'>
        <h2 style='color: #0d6efd;'>Hola, {$user->user_login}</h2>
        <p>Has solicitado restablecer tu contraseña en <strong>{$site_name}</strong>.</p>
        <p>Haz clic en el siguiente botón para continuar. Este enlace expirará en 1 hora.</p>
        <div style='text-align: center; margin: 30px 0;'>
          <a href='{$reset_link}' style='background-color: #0d6efd; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
            Restablecer Contraseña
          </a>
        </div>
        <p style='color: #666; font-size: 13px;'>Si no solicitaste este cambio, puedes ignorar este correo de forma segura.</p>
        <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
        <p style='font-size: 11px; color: #999;'>Este es un correo automático, por favor no respondas.</p>
      </div>
    ";

    // Enviar con MailService
    require_once BASE_DIR . '/core/services/MailService.php';
    
    $mail = (new MailService())
      ->name(MAIL_NAME)
      ->host(MAIL_HOST)
      ->email(MAIL_EMAIL)
      ->password(MAIL_PASSWORD)
      ->port(MAIL_PORT)
      ->encryption(MAIL_ENCRYPTION)
      ->init();

    $send = $mail->send($email, $subject, $body);

    if (!$send['success']) {
      // Registrar error pero informar éxito ambiguo por seguridad si el correo existe
      $log->error("Error enviando correo de reset: " . $send['message'])->file("auth")->write();
      
      echo json_encode([
        'success' => false,
        'message' => 'Hubo un problema al enviar el correo. Por favor intenta más tarde.'
      ], JSON_UNESCAPED_UNICODE);
      exit;
    }
  }

  // Respuesta exitosa (siempre exitosa por fuera para evitar enumeración de cuentas)
  echo json_encode([
    'success' => true,
    'message' => 'Si el correo está registrado, recibirás un enlace de recuperación en breve.'
  ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'Error interno del servidor.'
  ], JSON_UNESCAPED_UNICODE);
}
