<?php start_block("title"); ?>
Configuración de Captcha
<?php end_block(); ?>

<?php start_block("css"); ?>
<style>
  /* Suavizado de transición para la aparición de secciones */
  .fade-section {
    transition: opacity 0.3s ease, transform 0.3s ease;
  }

  .d-none {
    display: none !important;
  }
</style>
<?php end_block(); ?>

<form action="" method="post" autocomplete="off">
  <div class="card">
    <div class="card-body">

      <!-- Sección 1: Interruptor Maestro -->
      <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-3 border bg-body-tertiary">
        <div>
          <label class="form-check-label h6 mb-0" for="captcha_enabled">
            Estado del Sistema
          </label>
          <div class="small text-body-secondary">Activa o desactiva globalmente la protección por captcha.</div>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input h4 mb-0" type="checkbox" role="switch" id="captcha_enabled"
            name="captcha_enabled" value="1" <?php echo (!empty($optionsRaw['captcha_enabled']) && $optionsRaw['captcha_enabled'] == '1') ? 'checked' : ''; ?>>
        </div>
      </div>

      <!-- Contenedor Principal (Depende del switch maestro) -->
      <div id="main_config_wrapper"
        class="<?php echo (!empty($optionsRaw['captcha_enabled']) && $optionsRaw['captcha_enabled'] == '1') ? '' : 'd-none'; ?>">

        <!-- Selección de Tipo -->
        <div class="mb-4">
          <label for="captcha_type" class="form-label fw-bold text-body-secondary">
            <i class="fa-solid fa-list-check me-1"></i> Método de Verificación
          </label>
          <select class="form-select form-select-lg" name="captcha_type" id="captcha_type">
            <option value="vanilla" <?php echo (isset($optionsRaw['captcha_type']) && $optionsRaw['captcha_type'] == 'vanilla') ? 'selected' : ''; ?>>
              Vanilla Captcha (Interno)
            </option>
            <option value="recaptcha" <?php echo (isset($optionsRaw['captcha_type']) && $optionsRaw['captcha_type'] == 'recaptcha') ? 'selected' : ''; ?>>
              Google reCaptcha v2
            </option>
            <option value="cloudflare" <?php echo (isset($optionsRaw['captcha_type']) && $optionsRaw['captcha_type'] == 'cloudflare') ? 'selected' : ''; ?>>
              Cloudflare Turnstile (Recomendado)
            </option>
          </select>
        </div>

        <hr class="my-4 opacity-25">

        <!-- Sección: Vanilla Captcha Config -->
        <div id="section_vanilla"
          class="captcha-section <?php echo ($optionsRaw['captcha_type'] ?? 'vanilla') == 'vanilla' ? '' : 'd-none'; ?>">
          <div class="alert alert-info  d-flex align-items-center border-0" role="alert">
            <i class="fa-solid fa-circle-info fs-4 me-3"></i>
            <div>
              El captcha <strong>Vanilla</strong> utiliza procesamiento del lado del servidor local. No requiere APIs
              externas.
            </div>
          </div>
        </div>

        <!-- Sección: reCaptcha Config -->
        <div id="section_recaptcha"
          class="captcha-section <?php echo ($optionsRaw['captcha_type'] ?? '') == 'recaptcha' ? '' : 'd-none'; ?>">
          <div class="row g-4">
            <div class="col-12">
              <label for="google_recaptcha_site_key" class="form-label fw-semibold text-body-secondary">Site Key (Clave
                del Sitio)</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-lock text-primary"></i></span>
                <input type="text" class="form-control" id="google_recaptcha_site_key" name="google_recaptcha_site_key"
                  value="<?= $optionsRaw["google_recaptcha_site_key"] ?? "" ?>" placeholder="Ej: 6Lc_...">
              </div>
            </div>

            <div class="col-12">
              <label for="google_recaptcha_secret_key" class="form-label fw-semibold text-body-secondary">Secret Key
                (Clave Secreta)</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-key text-success"></i></span>
                <input type="text" class="form-control" id="google_recaptcha_secret_key"
                  name="google_recaptcha_secret_key" value="<?= $optionsRaw["google_recaptcha_secret_key"] ?? "" ?>"
                  placeholder="Ej: 6Lc_...">
              </div>
            </div>

            <div class="col-12">
              <div class="card bg-body-tertiary rounded-3 border-0">
                <div class="card-body p-3">
                  <div class="d-flex align-items-start">
                    <i class="fa-brands fa-google text-danger fs-5 me-3 mt-1"></i>
                    <div>
                      <p class="small mb-0 text-body-secondary">
                        Obtén tus credenciales en la <strong>Google Admin Console</strong>.
                      </p>
                      <a href="https://www.google.com/recaptcha/admin" target="_blank"
                        class="btn btn-link btn-sm p-0 mt-1">Ir a la consola <i
                          class="fa-solid fa-up-right-from-square ms-1" style="font-size: 0.7rem;"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sección: Cloudflare Turnstile Config -->
        <div id="section_cloudflare"
          class="captcha-section <?php echo ($optionsRaw['captcha_type'] ?? '') == 'cloudflare' ? '' : 'd-none'; ?>">
          <div class="row g-4">
            <div class="col-12">
              <label for="cloudflare_turnstile_site_key" class="form-label fw-semibold text-body-secondary">Turnstile
                Site Key</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-shield text-info"></i></span>
                <input type="text" class="form-control" id="cloudflare_turnstile_site_key"
                  name="cloudflare_turnstile_site_key" value="<?= $optionsRaw["cloudflare_turnstile_site_key"] ?? "" ?>"
                  placeholder="0x4AAAAAA...">
              </div>
            </div>

            <div class="col-12">
              <label for="cloudflare_turnstile_secret_key" class="form-label fw-semibold text-body-secondary">Turnstile
                Secret Key</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-vault text-warning"></i></span>
                <input type="text" class="form-control" id="cloudflare_turnstile_secret_key"
                  name="cloudflare_turnstile_secret_key"
                  value="<?= $optionsRaw["cloudflare_turnstile_secret_key"] ?? "" ?>" placeholder="0x4AAAAAA...">
              </div>
            </div>

            <div class="col-12">
              <div class="card bg-body-tertiary rounded-3 border-0 ">
                <div class="card-body p-3">
                  <div class="d-flex align-items-start">
                    <i class="fa-brands fa-cloudflare text-warning fs-5 me-3 mt-1"></i>
                    <div>
                      <p class="small mb-0 text-body-secondary">
                        <strong>Turnstile</strong> es la alternativa gratuita y amigable con la privacidad de Cloudflare
                        que no requiere que los usuarios resuelvan desafíos.
                      </p>
                      <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank"
                        class="btn btn-link btn-sm p-0 mt-1">Consola de Cloudflare <i
                          class="fa-solid fa-up-right-from-square ms-1" style="font-size: 0.7rem;"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    <div class="card-footer">
      <div class="row align-items-center">
        <div class="col-sm-6 text-body-secondary small mb-3 mb-sm-0">
          <i class="fa-solid fa-clock-rotate-left me-1"></i> Última actualización: <?php echo date('d/m/Y H:i'); ?>
        </div>
        <div class="col-sm-6 text-end">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Cambios
          </button>
        </div>
      </div>
    </div>
  </div>
</form>

<?php start_block("js"); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const masterSwitch = document.getElementById('captcha_enabled');
    const mainWrapper = document.getElementById('main_config_wrapper');
    const captchaTypeSelect = document.getElementById('captcha_type');

    const sectionVanilla = document.getElementById('section_vanilla');
    const sectionRecaptcha = document.getElementById('section_recaptcha');
    const sectionCloudflare = document.getElementById('section_cloudflare');

    // Función para manejar la visibilidad de las secciones de configuración
    function updateSections(selectedType) {
      // Ocultar todos
      [sectionVanilla, sectionRecaptcha, sectionCloudflare].forEach(s => s.classList.add('d-none'));

      // Mostrar el seleccionado
      if (selectedType === 'vanilla') {
        sectionVanilla.classList.remove('d-none');
      } else if (selectedType === 'recaptcha') {
        sectionRecaptcha.classList.remove('d-none');
      } else if (selectedType === 'cloudflare') {
        sectionCloudflare.classList.remove('d-none');
      }
    }

    // Toggle principal
    masterSwitch.addEventListener('change', function () {
      if (this.checked) {
        mainWrapper.classList.remove('d-none');
      } else {
        mainWrapper.classList.add('d-none');
      }
    });

    // Cambio de tipo de Captcha
    captchaTypeSelect.addEventListener('change', function () {
      updateSections(this.value);
    });
  });
</script>
<?php end_block(); ?>