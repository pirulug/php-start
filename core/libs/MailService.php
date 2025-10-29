<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once BASE_DIR . '/core/vendor/php-mailer/src/Exception.php';
require_once BASE_DIR . '/core/vendor/php-mailer/src/PHPMailer.php';
require_once BASE_DIR . '/core/vendor/php-mailer/src/SMTP.php';

class MailService {
  private PHPMailer $mail;
  private array $config = [];
  private bool $initialized = false;

  public function __construct() {
    $this->mail = new PHPMailer(true);
    $this->mail->isHTML(true);
    $this->mail->CharSet = 'UTF-8';
  }

  // Métodos encadenables
  public function host(?string $host): self {
    $this->config['host'] = trim((string) $host);
    return $this;
  }
  public function email(?string $email): self {
    $this->config['email'] = trim((string) $email);
    return $this;
  }
  public function password(?string $password): self {
    $this->config['password'] = trim((string) $password);
    return $this;
  }
  public function port(?int $port): self {
    $this->config['port'] = (int) $port;
    return $this;
  }
  public function encryption(?string $enc): self {
    $this->config['encryption'] = trim((string) $enc);
    return $this;
  }

  // Verifica si la configuración mínima está presente
  private function isConfigValid(): bool {
    $required = ['host', 'email', 'password', 'port'];
    foreach ($required as $key) {
      if (empty($this->config[$key])) {
        return false;
      }
    }
    return true;
  }

  // Inicializa PHPMailer solo si la config está completa
  public function init(): self {
    if (!$this->isConfigValid()) {
      $this->initialized = false;
      return $this;
    }

    $this->mail->isSMTP();
    $this->mail->SMTPAuth   = true;
    $this->mail->Host       = $this->config['host'];
    $this->mail->Username   = $this->config['email'];
    $this->mail->Password   = $this->config['password'];
    $this->mail->SMTPSecure = $this->config['encryption'] ?: PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->Port       = $this->config['port'];
    $this->mail->setFrom($this->config['email'], 'Sistema PHP-Start');

    $this->initialized = true;
    return $this;
  }

  // Envía el correo (si la config está correcta)
  public function send(string $to, string $subject, string $body, array $attachments = []): array {
    if (!$this->initialized || !$this->isConfigValid()) {
      return [
        "success" => false,
        "message" => "Falta configuración SMTP. No se pudo enviar el correo."
      ];
    }

    try {
      $this->mail->clearAddresses();
      $this->mail->clearAttachments();

      $this->mail->addAddress($to);
      $this->mail->Subject = $subject;
      $this->mail->Body    = $body;

      foreach ($attachments as $filePath) {
        if (file_exists($filePath)) {
          $this->mail->addAttachment($filePath);
        }
      }

      $this->mail->send();

      return [
        "success" => true,
        "message" => "Correo enviado correctamente a {$to}"
      ];
    } catch (Exception $e) {
      return [
        "success" => false,
        "message" => "Error al enviar el correo: {$this->mail->ErrorInfo}"
      ];
    }
  }
}
