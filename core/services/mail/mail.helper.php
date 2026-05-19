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
    require_once __DIR__ . "/mail.service.php";

    $mail = new MailService();
    $cfg = site_config();

    $settings = [
      "name"       => $cfg->get("site_name"),
      "host"       => $cfg->get("smtp_host"),
      "email"      => $cfg->get("smtp_email"),
      "password"   => $cfg->get("smtp_password"),
      "port"       => (int) $cfg->get("smtp_port"),
      "encryption" => $cfg->get("smtp_encryption"),
    ];

    if ($configOverride) {
      $settings = array_merge($settings, $configOverride);
    }

    return $mail
      ->name($settings["name"])
      ->host($settings["host"])
      ->email($settings["email"])
      ->password($settings["password"])
      ->port($settings["port"])
      ->encryption($settings["encryption"])
      ->init();
  }
}