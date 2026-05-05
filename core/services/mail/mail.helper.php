<?php

/**
 * Mail Utility Helper
 * Centralizes the access to MailService using SiteConfig
 */
class Mail {

  /**
   * Creates an initialized MailService instance
   * 
   * @param array|null $configOverride Optional configuration to override SiteConfig
   * @return MailService
   */
  public static function init(?array $configOverride = null): MailService {
    global $config;

    require_once __DIR__ . '/mail.service.php';

    $mail = new MailService();

    $settings = [
      'name'       => $config->get("site_name"),
      'host'       => $config->get("smtp_host"),
      'email'      => $config->get("smtp_email"),
      'password'   => $config->get("smtp_password"),
      'port'       => (int) $config->get("smtp_port"),
      'encryption' => $config->get("smtp_encryption"),
    ];

    if ($configOverride) {
      $settings = array_merge($settings, $configOverride);
    }

    return $mail
      ->name($settings['name'])
      ->host($settings['host'])
      ->email($settings['email'])
      ->password($settings['password'])
      ->port($settings['port'])
      ->encryption($settings['encryption'])
      ->init();
  }
}