<form action="" method="post">

  <div class="card mt-4">
    <div class="card-body">
      <h4 class="mb-4 border-bottom pb-2">Google reCAPTCHA Settings</h4>

      <!-- Habilitar reCAPTCHA -->
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="google_recaptcha_enabled" name="google_recaptcha_enabled"
          value="1" <?php if (!empty($optionsRaw['google_recaptcha_enabled']) && $optionsRaw['google_recaptcha_enabled'] == '1')
            echo 'checked'; ?> onclick="recaptchaKeysToggle()">
        <label class="form-check-label fw-semibold" for="google_recaptcha_enabled">
          Activar Google reCAPTCHA
        </label>
      </div>

      <!-- Sección de claves -->
      <div id="recaptcha_keys_section" style="display: <?= $recaptcha_enabled ? 'block' : 'none' ?>;">
        <div class="mb-3">
          <label for="google_recaptcha_site_key" class="form-label fw-semibold">Site Key</label>
          <input type="text" class="form-control" id="google_recaptcha_site_key" name="google_recaptcha_site_key"
            value="<?= $optionsRaw["google_recaptcha_site_key"] ?? "" ?>" placeholder="Tu Site Key de Google reCAPTCHA">
        </div>

        <div class="mb-3">
          <label for="google_recaptcha_secret_key" class="form-label fw-semibold">Secret Key</label>
          <input type="text" class="form-control" id="google_recaptcha_secret_key" name="google_recaptcha_secret_key"
            value="<?= $optionsRaw["google_recaptcha_secret_key"] ?? "" ?>"
            placeholder="Tu Secret Key de Google reCAPTCHA">
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
  </div>
</form>

<script>
  function recaptchaKeysToggle() {
    const checkbox = document.getElementById('google_recaptcha_enabled');
    const keysSection = document.getElementById('recaptcha_keys_section');
    keysSection.style.display = checkbox.checked ? 'block' : 'none';
  }

  document.addEventListener('DOMContentLoaded', recaptchaKeysToggle);
</script>