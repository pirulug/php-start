<?php


require __DIR__ . "/../../core/init.php";
require BASE_DIR . '/core/vendor/php-mailer/src/Exception.php';
require BASE_DIR . '/core/vendor/php-mailer/src/PHPMailer.php';
require BASE_DIR . '/core/vendor/php-mailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Obtener datos del formulario
  $host     = $_POST['st_smtphost'] ?? '';
  $email    = $_POST['st_smtpemail'] ?? '';
  $password = $_POST['st_smtppassword'] ?? '';
  $port     = $_POST['st_smtpport'] ?? '';
  $encrypt  = $_POST['st_smtpencrypt'] ?? '';
  $destino  = $_POST['destinatario'] ?? $email;

  $mail = new PHPMailer(true);

  try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $email;
    $mail->Password   = $password;
    $mail->SMTPSecure = $encrypt ?: PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int) $port ?: 587;

    // Remitente y destinatario
    $mail->setFrom($email, 'Prueba SMTP');
    $mail->addAddress($destino); // ← se envía al correo ingresado

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Correo de prueba desde tu sistema';
    $mail->Body    = '✅ Este es un correo de prueba enviado con los parámetros actuales.';

    $mail->send();

    echo json_encode([
      "success" => true,
      "message" => "Correo enviado de manera correcta."
    ]);
  } catch (Exception $e) {
    echo json_encode([
      "success" => false,
      "message" => "Error: {$mail->ErrorInfo}"
    ]);
  }
} else {
  echo json_encode([
    "success" => false,
    "message" => "Datos Faltantes"
  ]);
}