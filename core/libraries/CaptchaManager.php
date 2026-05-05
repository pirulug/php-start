<?php

/**
 * CaptchaManager
 *
 * Clase encargada de la gestión y renderizado de diferentes sistemas de captcha.
 * Soporta Captcha nativo (Vanilla), Google reCAPTCHA v2 y Cloudflare Turnstile.
 * Proporciona una interfaz fluida para la configuración y validación.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class CaptchaManager {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE CONFIGURACIÓN
  // --------------------------------------------------------------------------

  protected bool $enabled = true;
  protected string $type = 'vanilla';

  protected string $googleSiteKey = '';
  protected string $googleSecretKey = '';

  protected string $cloudflareSiteKey = '';
  protected string $cloudflareSecretKey = '';

  // --------------------------------------------------------------------------
  // SECCIÓN: CONFIGURACIÓN (FLUENT API)
  // --------------------------------------------------------------------------

  /**
   * Activa o desactiva el sistema de captcha.
   *
   * @param bool $value Estado.
   * @return self Instancia.
   */
  public function enabled(bool $value): self {
    $this->enabled = $value;
    return $this;
  }

  /**
   * Define el tipo de captcha a utilizar (vanilla, recaptcha, cloudflare).
   *
   * @param string $type Tipo de captcha.
   * @return self Instancia.
   */
  public function type(string $type): self {
    $this->type = $type;
    return $this;
  }

  /**
   * Define la Site Key para Google reCAPTCHA.
   *
   * @param string $key Clave del sitio.
   * @return self Instancia.
   */
  public function google_recaptcha_site_key(string $key): self {
    $this->googleSiteKey = $key;
    return $this;
  }

  /**
   * Define la Secret Key para Google reCAPTCHA.
   *
   * @param string $key Clave secreta.
   * @return self Instancia.
   */
  public function google_recaptcha_secret_key(string $key): self {
    $this->googleSecretKey = $key;
    return $this;
  }

  /**
   * Define la Site Key para Cloudflare Turnstile.
   *
   * @param string $key Clave del sitio.
   * @return self Instancia.
   */
  public function cloudflare_site_key(string $key): self {
    $this->cloudflareSiteKey = $key;
    return $this;
  }

  /**
   * Define la Secret Key para Cloudflare Turnstile.
   *
   * @param string $key Clave secreta.
   * @return self Instancia.
   */
  public function cloudflare_secret_key(string $key): self {
    $this->cloudflareSecretKey = $key;
    return $this;
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: RENDERIZADO
  // --------------------------------------------------------------------------

  /**
   * Genera el código HTML necesario para mostrar el captcha en la vista.
   *
   * @return string Código HTML generado.
   */
  public function render(): string {
    if (!$this->enabled) {
      return '';
    }

    switch ($this->type) {
      case 'vanilla':
        return '
          <div class="mb-3">
            <img src="' . front_route("captcha/img.webp") . '" class="d-block mb-2 rounded border" height="45">
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

  // --------------------------------------------------------------------------
  // SECCIÓN: VALIDACIÓN
  // --------------------------------------------------------------------------

  /**
   * Valida el captcha enviado en la petición.
   *
   * @param array $post Datos de la petición ($_POST).
   * @return bool True si la validación es exitosa.
   */
  public function validate(array $post): bool {
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

  // --------------------------------------------------------------------------
  // SECCIÓN: LÓGICA INTERNA DE VALIDACIÓN
  // --------------------------------------------------------------------------

  /**
   * Verifica la respuesta del reCAPTCHA contra los servidores de Google.
   *
   * @param array $post Datos de la petición.
   * @return bool
   */
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

  /**
   * Verifica el token de Turnstile contra los servidores de Cloudflare.
   *
   * @param array $post Datos de la petición.
   * @return bool
   */
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