<?php

require_once BASE_DIR . '/core/services/MailService.php';

// Validación del método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode([
    'success' => false,
    'message' => 'Método no permitido. Use POST.'
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

// Lectura y normalización de datos
$to      = trim($_POST['to'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$body    = trim($_POST['body'] ?? '');

// Contenedor de errores UX
$errors = [];

// Validación del campo correo
if ($to === '') {
  $errors['to'] = 'El correo de destino es obligatorio.';
} elseif (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
  $errors['to'] = 'El correo de destino no tiene un formato válido.';
}

// Validación del asunto
if ($subject === '') {
  $errors['subject'] = 'El asunto es obligatorio.';
} elseif (mb_strlen($subject) > 150) {
  $errors['subject'] = 'El asunto no puede superar los 150 caracteres.';
}

// Validación del cuerpo del mensaje
if ($body === '') {
  $errors['body'] = 'El mensaje es obligatorio.';
} elseif (mb_strlen($body) < 10) {
  $errors['body'] = 'El mensaje debe tener al menos 10 caracteres.';
}

// Respuesta con errores de validación
if (!empty($errors)) {
  http_response_code(422);
  echo json_encode([
    'success' => false,
    'message' => 'Existen errores de validación.',
    'errors'  => $errors
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

// Envío del correo
try {

  $mail = (new MailService())
    ->name(MAIL_NAME)
    ->host(MAIL_HOST)
    ->email(MAIL_EMAIL)
    ->password(MAIL_PASSWORD)
    ->port(MAIL_PORT)
    ->encryption(MAIL_ENCRYPTION)
    ->init();

  $response = $mail->send($to, $subject, $body);

  http_response_code($response['success'] ? 200 : 500);

  echo json_encode([
    'success' => (bool) $response['success'],
    'message' => $response['message']
  ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'Error interno al enviar el correo.'
  ], JSON_UNESCAPED_UNICODE);

}
