<?php start_block("title"); ?>
Google reCAPTCHA v3
<?php end_block(); ?>

<?php start_block("js"); ?>

<?php end_block(); ?>

<?php start_block("css"); ?>

<?php end_block(); ?>


<form action="" method="post" autocomplete="off">

  <div class="card">

    <div class="card-body">

      <div class="d-flex justify-content-between align-items-center mb-4 p-3 border rounded bg-light bg-opacity-10">
        <div>
          <label class="form-check-label fw-semibold mb-0" for="google_recaptcha_enabled">
            Activar Protección
          </label>
          <div class="small text-muted">Habilita la verificación de seguridad en los formularios.</div>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="google_recaptcha_enabled"
            name="google_recaptcha_enabled" value="1" <?php if (!empty($optionsRaw['google_recaptcha_enabled']) && $optionsRaw['google_recaptcha_enabled'] == '1')
              echo 'checked'; ?>>
        </div>
      </div>

      <div id="recaptcha_keys_section"
        class="<?= (!empty($optionsRaw['google_recaptcha_enabled']) && $optionsRaw['google_recaptcha_enabled'] == '1') ? '' : 'd-none' ?>">
        <div class="row g-3">

          <div class="col-12 col-md-6">
            <label for="google_recaptcha_site_key" class="form-label">Site Key (Clave del Sitio)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
              <input type="text" class="form-control" id="google_recaptcha_site_key" name="google_recaptcha_site_key"
                value="<?= $optionsRaw["google_recaptcha_site_key"] ?? "" ?>" placeholder="Ej: 6Lc_...">
            </div>
          </div>

          <div class="col-12 col-md-6">
            <label for="google_recaptcha_secret_key" class="form-label">Secret Key (Clave Secreta)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
              <input type="text" class="form-control" id="google_recaptcha_secret_key"
                name="google_recaptcha_secret_key" value="<?= $optionsRaw["google_recaptcha_secret_key"] ?? "" ?>"
                placeholder="Ej: 6Lc_...">
            </div>
          </div>

          <div class="col-12">
            <div class="form-text text-muted">
              <i class="fa-solid fa-circle-info me-1"></i>
              Debes generar estas claves en la consola de Google Admin Console.
            </div>
          </div>

        </div>
      </div>

    </div>

    <div class="card-footer bg-transparent py-3 text-end">
      <button type="submit" class="btn btn-primary px-4">
        <i class="fa-solid fa-floppy-disk me-2"></i>
        Guardar Cambios
      </button>
    </div>
  </div>

</form>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('google_recaptcha_enabled');
    const keysSection = document.getElementById('recaptcha_keys_section');

    checkbox.addEventListener('change', function () {
      if (this.checked) {
        keysSection.classList.remove('d-none');
        keysSection.classList.add('fade-in');
      } else {
        keysSection.classList.add('d-none');
      }
    });
  });
</script>