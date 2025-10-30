<div class="card  mt-4">
  <div class="card-body">
    <h4 class="mb-4 border-bottom pb-2">Google reCAPTCHA Settings</h4>

    <!-- Enable reCAPTCHA -->
    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" id="google_recaptcha_enabled" name="google_recaptcha_enabled"
        onclick="recaptchKeysHideShow()">
      <label class="form-check-label fw-semibold" for="google_recaptcha_enabled">
        Enable Google reCAPTCHA
      </label>
    </div>

    <div id="recaptcha_keys_section">
      <!-- reCAPTCHA Site Key -->
      <div class="mb-3">
        <label for="recaptcha_site_key" class="form-label fw-semibold">Site Key</label>
        <input type="text" class="form-control" id="recaptcha_site_key" name="recaptcha_site_key"
          value="6LdIWswUAAAAAMRp6xt2wBu7V59jUvZvKWf_rbJc">
      </div>

      <!-- reCAPTCHA Secret Key -->
      <div class="mb-3">
        <label for="recaptcha_secret_key" class="form-label fw-semibold">Secret Key</label>
        <input type="text" class="form-control" id="recaptcha_secret_key" name="recaptcha_secret_key"
          value="6LdIWswUAAAAAIsdboq_76c63PHFsOPJHNR-z-75">
      </div>
    </div>
  </div>
</div>

<script>
  function recaptchKeysHideShow() {
    const checkbox = document.getElementById('google_recaptcha_enabled');
    const keysSection = document.getElementById('recaptcha_keys_section');

    if (checkbox.checked) {
      keysSection.style.display = 'block';
    } else {
      keysSection.style.display = 'none';
    }
  }

  // Ejecutar al cargar la página (por si el checkbox viene marcado)
  document.addEventListener('DOMContentLoaded', () => {
    recaptchKeysHideShow();
  });
</script>