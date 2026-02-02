<?php

class CaptchaManager {
  protected bool $enabled = true;
  protected string $type = 'vanilla';

  // Google reCAPTCHA
  protected string $googleSiteKey = '';
  protected string $googleSecretKey = '';

  // Cloudflare Turnstile
  protected string $cloudflareSiteKey = '';
  protected string $cloudflareSecretKey = '';

  /* =====================
   | Configuración (Fluent)
   ===================== */

  public function enabled(bool $value): self {
    $this->enabled = $value;
    return $this;
  }

  public function type(string $type): self {
    $this->type = $type;
    return $this;
  }

  public function google_recaptcha_site_key(string $key): self {
    $this->googleSiteKey = $key;
    return $this;
  }

  public function google_recaptcha_secret_key(string $key): self {
    $this->googleSecretKey = $key;
    return $this;
  }

  public function cloudflare_site_key(string $key): self {
    $this->cloudflareSiteKey = $key;
    return $this;
  }

  public function cloudflare_secret_key(string $key): self {
    $this->cloudflareSecretKey = $key;
    return $this;
  }

  /* =====================
   | Render
   ===================== */

  public function render(): string {
    if (!$this->enabled) {
      return '';
    }

    switch ($this->type) {

      case 'vanilla':
        return '
                    <div class="mb-3">
                        <img src="' . admin_route("plugins/img") . '" class="d-block mb-2 rounded border" height="45">
                        <input type="text" name="captcha" class="form-control" placeholder="Ingresa el código" required>
                    </div>
                ';

      case 'recaptcha':
        return '
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="' . $this->googleSiteKey . '"></div>
                    </div>
                ';

      case 'cloudflare':
        return '
                    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                    <div class="cf-turnstile" data-sitekey="' . $this->cloudflareSiteKey . '"></div>
                ';

      default:
        return '';
    }
  }

  /* =====================
   | Validación
   ===================== */

  public function validate(array $post): bool {
    // 👉 Si está desactivado, siempre pasa
    if (!$this->enabled) {
      return true;
    }

    switch ($this->type) {

      case 'vanilla':
        return \Captcha::validate($post['captcha'] ?? '');

      case 'recaptcha':
        return $this->validateRecaptcha($post);

      case 'cloudflare':
        return $this->validateCloudflare($post);

      default:
        return false;
    }
  }

  /* =====================
   | Internos
   ===================== */

  protected function validateRecaptcha(array $post): bool {
    $response = $post['g-recaptcha-response'] ?? '';

    if (!$response) {
      return false;
    }

    $verify = file_get_contents(
      "https://www.google.com/recaptcha/api/siteverify" .
      "?secret={$this->googleSecretKey}&response={$response}"
    );

    return (json_decode($verify)->success ?? false) === true;
  }

  protected function validateCloudflare(array $post): bool {
    $token  = $post['cf-turnstile-response'] ?? '';
    $secret = $this->cloudflareSecretKey;

    if (!$token || !$secret) {
      return false;
    }

    $data = [
      'secret'   => $secret,
      'response' => $token,
      'remoteip' => $_SERVER['HTTP_CF_CONNECTING_IP']
        ?? $_SERVER['HTTP_X_FORWARDED_FOR']
        ?? $_SERVER['REMOTE_ADDR']
    ];

    $options = [
      'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'timeout' => 5
      ]
    ];

    $context = stream_context_create($options);
    $result  = file_get_contents(
      'https://challenges.cloudflare.com/turnstile/v0/siteverify',
      false,
      $context
    );

    if ($result === false) {
      return false;
    }

    return (json_decode($result, true)['success'] ?? false) === true;
  }
}
