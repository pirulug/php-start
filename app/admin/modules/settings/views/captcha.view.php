<?php start_block('title') ?>
Captcha
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Settings'],
  ['label' => 'Captcha']
]) ?>
<?php end_block(); ?>

<?php
$captcha = $config->captcha();
?>

<?php start_block("js"); ?>
<?= url_script_admin('settings', 'captcha') ?>
<?php end_block(); ?>

<form action="" method="post" autocomplete="off">
  <div class="card">
    <div class="card-body">

      <!-- Sección 1: Interruptor Maestro -->
      <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3 border bg-body">
        <div>
          <label class="form-check-label h6 mb-0" for="captcha_enabled">
            Estado del Sistema
          </label>
          <div class="small text-body-secondary">Activa o desactiva globalmente la protección por captcha.</div>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input h4 mb-0" type="checkbox" role="switch" id="captcha_enabled"
            name="captcha_enabled" value="1" <?= $captcha->enabled ? 'checked' : ''; ?>>
        </div>
      </div>

      <!-- Contenedor Principal (Depende del switch maestro) -->
      <div id="main_config_wrapper" class="<?= $captcha->enabled ? '' : 'd-none'; ?>">

        <div class="mb-3">
          <label for="captcha_type" class="form-label fw-bold text-body-secondary">
            <i class="fa-solid fa-list-check me-1"></i> Método de Verificación
          </label>
          <select class="form-select form-select-lg" name="captcha_type" id="captcha_type">
            <option value="vanilla" <?= $captcha->type == 'vanilla' ? 'selected' : ''; ?>>
              Vanilla Captcha (Interno)
            </option>
            <option value="recaptcha" <?= $captcha->type == 'recaptcha' ? 'selected' : ''; ?>>
              Google reCaptcha v2
            </option>
            <option value="cloudflare" <?= $captcha->type == 'cloudflare' ? 'selected' : ''; ?>>
              Cloudflare Turnstile (Recomendado)
            </option>
          </select>
        </div>

        <hr class="my-3 opacity-25">

        <!-- Sección: Vanilla Captcha Config -->
        <div id="section_vanilla" class="captcha-section <?= $captcha->type == 'vanilla' ? '' : 'd-none'; ?>">
          <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-info fs-4 me-3"></i>
            <div>
              El captcha <strong>Vanilla</strong> utiliza procesamiento del lado del servidor local. No requiere APIs
              externas.
            </div>
          </div>
        </div>

        <!-- Sección: reCaptcha Config -->
        <div id="section_recaptcha" class="captcha-section <?= $captcha->type == 'recaptcha' ? '' : 'd-none'; ?>">
          <div class="row g-3">
            <div class="col-12">
              <label for="google_recaptcha_site_key" class="form-label fw-semibold text-body-secondary">Site Key (Clave
                del Sitio)</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-lock text-primary"></i></span>
                <input type="text" class="form-control" id="google_recaptcha_site_key" name="google_recaptcha_site_key"
                  value="<?= $captcha->google->site_key ?>" placeholder="Ej: 6Lc_...">
              </div>
            </div>

            <div class="col-12">
              <label for="google_recaptcha_secret_key" class="form-label fw-semibold text-body-secondary">Secret Key
                (Clave Secreta)</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-key text-success"></i></span>
                <input type="text" class="form-control" id="google_recaptcha_secret_key"
                  name="google_recaptcha_secret_key" value="<?= $captcha->google->secret_key ?>"
                  placeholder="Ej: 6Lc_...">
              </div>
            </div>

            <div class="col-12">
              <div class="card bg-body rounded-3">
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
        <div id="section_cloudflare" class="captcha-section <?= $captcha->type == 'cloudflare' ? '' : 'd-none'; ?>">
          <div class="row g-3">
            <div class="col-12">
              <label for="cloudflare_turnstile_site_key" class="form-label fw-semibold text-body-secondary">Turnstile
                Site Key</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-shield text-info"></i></span>
                <input type="text" class="form-control" id="cloudflare_turnstile_site_key"
                  name="cloudflare_turnstile_site_key" value="<?= $captcha->cloudflare->site_key ?>"
                  placeholder="0x4AAAAAA...">
              </div>
            </div>

            <div class="col-12">
              <label for="cloudflare_turnstile_secret_key" class="form-label fw-semibold text-body-secondary">Turnstile
                Secret Key</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-vault text-warning"></i></span>
                <input type="text" class="form-control" id="cloudflare_turnstile_secret_key"
                  name="cloudflare_turnstile_secret_key" value="<?= $captcha->cloudflare->secret_key ?>"
                  placeholder="0x4AAAAAA...">
              </div>
            </div>

            <div class="col-12">
              <div class="card bg-body rounded-3">
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
  </div>

  <div class="bg-body p-3 mt-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
    <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
      <i class="fa-solid fa-floppy-disk me-2"></i>
      Guardar Cambios
    </button>
  </div>
</form>