<?php

require_once "../../core.php";

// Incluye las clases de PHPMailer
require BASE_DIR . '/libs/phpmailer/src/PHPMailer.php';
require BASE_DIR . '/libs/phpmailer/src/SMTP.php';
require BASE_DIR . '/libs/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Obtener SMTP de la base de datos
$query = "SELECT * FROM smtp";
$smtp  = $connect->query($query)->fetch(PDO::FETCH_OBJ);

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);

try {
  // Configuración del servidor SMTP
  $mail->isSMTP();                                            // Usar SMTP
  $mail->Host       = $smtp->st_smtphost;                     // Servidor SMTP de Gmail
  $mail->SMTPAuth   = true;                                   // Habilitar autenticación SMTP
  $mail->Username   = $smtp->st_smtpemail;                    // Tu dirección de correo electrónico
  $mail->Password   = $smtp->st_smtppassword;                 // Tu contraseña de Gmail o contraseña de aplicación
  $mail->SMTPSecure = $smtp->st_smtpencrypt;                  // Habilitar encriptación TLS; `PHPMailer::ENCRYPTION_SMTPS` si usas `SSL`
  $mail->Port       = $smtp->st_smtpport;                     // Puerto TCP; 587 para TLS, 465 para SSL

  // $mail->SMTPDebug = 3;

  // Configuración del correo
  $mail->setFrom('gele.omfg@gmail.com', 'Hola mundo 2');
  $mail->addAddress('guidolaes@gmail.com', 'Guido Laime');
  $mail->Subject = 'Asunto del correo';
  $mail->Body    = 'Este es el <b>cuerpo</b> del correo en texto plano.';
  $mail->AltBody = 'Este es el cuerpo del correo en texto plano para clientes que no soportan HTML.';

  // Enviar el correo
  $mail->send();
  echo 'El mensaje se envió correctamente';
} catch (Exception $e) {
  echo "El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}";
}
