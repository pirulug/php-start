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

  // Soporte para modo encadenado
  private ?string $to = null;
  private ?string $subject = null;
  private ?string $body = null;
  private array $attachments = [];

  public function __construct() {
    $this->mail = new PHPMailer(true);
    $this->mail->isHTML(true);
    $this->mail->CharSet = 'UTF-8';
  }

  // ===== CONFIGURACIÓN SMTP =====
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

  private function isConfigValid(): bool {
    $required = ['host', 'email', 'password', 'port'];
    foreach ($required as $key) {
      if (empty($this->config[$key]))
        return false;
    }
    return true;
  }

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

  // ===== NUEVO: MÉTODOS ENCADENADOS =====
  public function to(string $to): self {
    $this->to = $to;
    return $this;
  }

  public function subject(string $subject): self {
    $this->subject = $subject;
    return $this;
  }

  public function body(string $body): self {
    $this->body = $body;
    return $this;
  }

  public function attach(string $filePath): self {
    if (file_exists($filePath)) {
      $this->attachments[] = $filePath;
    }
    return $this;
  }

  // ===== ENVÍO PRINCIPAL =====
  public function send(?string $to = null, ?string $subject = null, ?string $body = null, array $attachments = []): array {
    if (!$this->initialized || !$this->isConfigValid()) {
      return [
        "success" => false,
        "message" => "Falta configuración SMTP. No se pudo enviar el correo."
      ];
    }

    // Si se llama en modo encadenado, usar los valores almacenados
    $to          = $to ?? $this->to;
    $subject     = $subject ?? $this->subject;
    $body        = $body ?? $this->body;
    $attachments = array_merge($this->attachments, $attachments);

    if (empty($to) || empty($subject) || empty($body)) {
      return [
        "success" => false,
        "message" => "Faltan datos del correo (destinatario, asunto o cuerpo)."
      ];
    }

    file_put_contents(BASE_DIR . "/logs/debug_mail.log", date("Y-m-d H:i:s") . " - Enviando correo...\n", FILE_APPEND);

    try {
      $this->mail->clearAddresses();
      $this->mail->clearAttachments();

      $this->mail->addAddress($to);
      $this->mail->Subject = $subject;
      $this->mail->Body    = $body;

      foreach ($attachments as $filePath) {
        $this->mail->addAttachment($filePath);
      }

      $this->mail->send();

      // Reset parcial tras enviar
      $this->to          = $this->subject = $this->body = null;
      $this->attachments = [];

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
