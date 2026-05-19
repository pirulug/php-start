<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once BASE_DIR . '/core/vendors/php-mailer/src/Exception.php';
require_once BASE_DIR . '/core/vendors/php-mailer/src/PHPMailer.php';
require_once BASE_DIR . '/core/vendors/php-mailer/src/SMTP.php';

/**
 * Class MailService
 * Handles the low-level email sending logic using PHPMailer.
 */
class MailService {

  private PHPMailer $mail;

  private array $config = [
    'host'       => null,
    'email'      => null,
    'password'   => null,
    'port'       => null,
    'encryption' => null,
    'name'       => 'Sistema PHP-Start'
  ];

  private ?string $to = null;
  private ?string $subject = null;
  private ?string $body = null;
  private array $attachments = [];
  private ?string $lang = null;

  private bool $initialized = false;

  public function __construct() {
    $this->mail = new PHPMailer(true);
    $this->mail->isHTML(true);
    $this->mail->CharSet = "UTF-8";
  }

  public function lang($lang) {
    $this->lang = trim($lang);
    return $this;
  }

  public function host(string $host): self {
    $this->config['host'] = trim($host);
    return $this;
  }

  public function email(string $email): self {
    $this->config['email'] = trim($email);
    return $this;
  }

  public function password(string $password): self {
    $this->config['password'] = trim($password);
    return $this;
  }

  public function port(int $port): self {
    $this->config['port'] = $port;
    return $this;
  }

  public function encryption(string $encryption): self {
    $this->config['encryption'] = trim($encryption);
    return $this;
  }

  public function name($name) {
    $this->config['name'] = trim($name);
    return $this;
  }

  public function init(): self {
    if (!$this->configIsValid()) {
      $this->initialized = false;
      return $this;
    }

    $this->mail->isSMTP();
    $this->mail->SMTPAuth   = true;
    $this->mail->Host       = $this->config['host'];
    $this->mail->Username   = $this->config['email'];
    $this->mail->Password   = $this->config['password'];
    $this->mail->Port       = $this->config['port'];
    $this->mail->SMTPSecure = $this->config['encryption'] ?: PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->setFrom($this->config['email'], $this->config['name']);

    $this->initialized = true;
    return $this;
  }

  public function to(string $to): self {
    $this->to = trim($to);
    return $this;
  }

  public function subject(string $subject): self {
    $this->subject = trim($subject);
    return $this;
  }

  public function body(string $body): self {
    $this->body = $body;
    return $this;
  }

  public function attach(string $filePath): self {
    if (is_file($filePath)) {
      $this->attachments[] = $filePath;
    }
    return $this;
  }

  public function send(
    $to = null,
    $subject = null,
    $body = null,
    $attachments = [],
    $bypassQueue = false
  ) {
    $to      = $to ?? $this->to;
    $subject = $subject ?? $this->subject;
    $body    = $body ?? $this->body;
    $attachments = array_merge($this->attachments, $attachments);
    $lang = $this->lang ?? get_locale();

    if (!$to || !$subject || !$body) {
      return [
        "success" => false,
        "message" => "Destinatario, asunto o cuerpo no definidos."
      ];
    }

    // Guardar en cola si está habilitada y no se solicita bypass
    if (!$bypassQueue && site_config()->get("mail_queue_enabled") === "true") {
      $mails_dir = BASE_DIR . "/storage/mails";
      if (!is_dir($mails_dir)) {
        mkdir($mails_dir, 0755, true);
      }

      $mail_data = [
        "to"          => $to,
        "subject"     => $subject,
        "body"        => $body,
        "attachments" => $attachments,
        "lang"        => $lang,
        "created_at"  => date("Y-m-d H:i:s")
      ];

      $filename = "mail_" . date("d-m-Y-H-i-s") . "_" . bin2hex(random_bytes(4)) . ".json";
      $file_path = $mails_dir . "/" . $filename;

      if (file_put_contents($file_path, json_encode($mail_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
        $this->resetMessage();
        return [
          "success" => true,
          "message" => "Correo encolado correctamente."
        ];
      } else {
        return [
          "success" => false,
          "message" => "No se pudo encolar el correo en el archivo."
        ];
      }
    }

    if (!$this->initialized || !$this->configIsValid()) {
      return [
        "success" => false,
        "message" => "Configuración SMTP incompleta."
      ];
    }

    try {
      $this->mail->clearAddresses();
      $this->mail->clearAttachments();
      $this->mail->clearCustomHeaders();

      $this->mail->addCustomHeader("Content-Language", $lang);
      $this->mail->addAddress($to);
      $this->mail->Subject = $subject;
      $this->mail->Body    = $body;

      foreach ($attachments as $file) {
        $this->mail->addAttachment($file);
      }

      $this->mail->send();
      $this->resetMessage();

      return [
        "success" => true,
        "message" => "Correo enviado correctamente a {$to}"
      ];

    } catch (Exception $e) {
      return [
        "success" => false,
        "message" => $this->mail->ErrorInfo
      ];
    }
  }

  private function configIsValid(): bool {
    return !empty($this->config['host'])
      && !empty($this->config['email'])
      && !empty($this->config['password'])
      && !empty($this->config['port']);
  }

  private function resetMessage(): void {
    $this->to          = null;
    $this->subject     = null;
    $this->body        = null;
    $this->attachments = [];
  }
}