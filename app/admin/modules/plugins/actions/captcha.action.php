<?php

$captcha = new CaptchaManager();

$captcha
  ->enabled($config->get('captcha_enabled'))
  ->type($config->get('captcha_type'))
  ->google_recaptcha_site_key($config->get('google_recaptcha_site_key'))
  ->google_recaptcha_secret_key($config->get('google_recaptcha_secret_key'))
  ->cloudflare_site_key($config->get('cloudflare_turnstile_site_key'))
  ->cloudflare_secret_key($config->get('cloudflare_turnstile_secret_key'))
;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $texto = $_POST['texto'] ?? '';

  if (!$captcha->validate($_POST)) {
    $notifier->message("Captcha incorrecto.")->bootstrap()->danger()->add();
    // header("Refresh:0");
    exit;
  }

  $notifier->message($texto)->bootstrap()->info()->add();

  header("Location: " . admin_route("plugins/captcha"));
  exit();

}
