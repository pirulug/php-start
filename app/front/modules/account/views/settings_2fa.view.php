<?php start_block('title'); ?>
Autenticación de Doble Factor (2FA)
<?php end_block(); ?>

<?php start_block('js'); ?>
<?php if (!$is_2fa_enabled): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Generar el código QR de configuración
    new QRious({
      element: document.getElementById('qr-code-canvas'),
      value: '<?= $qr_code_url ?>',
      size: 160
    });

    const inputs = document.querySelectorAll('.verification-code-input');
    const hiddenInput = document.getElementById('2fa_code_hidden');
    const form = hiddenInput.closest('form');

    inputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
        // Permitir solo números
        e.target.value = e.target.value.replace(/[^0-9]/g, '');

        if (e.target.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }

        updateHiddenValue();
      });

      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
          inputs[index - 1].focus();
        }
      });

      input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pastedData = e.clipboardData.getData('text').trim();
        if (/^\d{6}$/.test(pastedData)) {
          inputs.forEach((inp, idx) => {
            inp.value = pastedData[idx];
          });
          updateHiddenValue();
          inputs[5].focus();
        }
      });
    });

    function updateHiddenValue() {
      let code = '';
      inputs.forEach(inp => code += inp.value);
      hiddenInput.value = code;
    }
  });
</script>
<?php endif; ?>
<?php block_end(); ?>

<div class="container my-3">
  <div class="row g-3">

    <!-- SIDEBAR DE NAVEGACIÓN -->
    <div class="col-md-4 col-lg-3">
      <?php require_once BASE_DIR . '/app/front/modules/account/views/partials/sidebar.php'; ?>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="col-md-8 col-lg-9">
      
      <?php if ($is_2fa_enabled): ?>
        <!-- 2FA ACTIVADO -->
        <form action="" method="POST">
          <div class="card mb-3">
            <div class="card-header py-3 d-flex align-items-center">
              <h6 class="card-title mb-0 fw-bold text-uppercase">
                <i class="fa-solid fa-shield-check me-2 text-success"></i><?= __("Autenticación en Dos Pasos Activa") ?>
              </h6>
            </div>

            <div class="card-body">
              <div class="alert alert-success border-0 bg-success-subtle mb-3 d-flex align-items-center gap-3 p-3" role="alert">
                <div class="bg-body p-2 rounded-3 border border-success-subtle">
                  <i class="fa-solid fa-circle-check text-success fs-4"></i>
                </div>
                <div>
                  <strong class="d-block mb-1"><?= __("Tu cuenta está protegida") ?></strong>
                  <div class="small text-body-secondary">
                    <?= __("La autenticación en dos factores (2FA) está actualmente activa en tu cuenta. Cada vez que inicies sesión se te solicitará un código de seguridad temporal.") ?>
                  </div>
                </div>
              </div>

              <!-- CONTRASEÑA PARA DESACTIVAR -->
              <div class="mb-3">
                <label for="current_password" class="form-label"><?= __("Contraseña Actual") ?></label>
                <div class="input-group">
                  <input type="password" id="current_password" name="current_password" class="form-control" placeholder="••••••••••••" data-pr-toggle-password="" required>
                </div>
                <div class="form-text small mt-2">
                  <?= __("Por seguridad, debes ingresar tu contraseña para poder desactivar la protección de doble factor.") ?>
                </div>
              </div>
            </div>
          </div>

          <!-- BOTONERA DE DESACTIVACIÓN -->
          <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom mt-3">
            <a href="<?= front_route("account/profile") ?>" class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
              <i class="fa-solid fa-arrow-left me-2"></i> <?= __("Volver") ?>
            </a>
            <button name="disable_2fa" type="submit" class="btn btn-danger px-5 text-uppercase small fw-bold">
              <i class="fa-solid fa-shield-slash me-2"></i> <?= __("Desactivar 2FA") ?>
            </button>
          </div>
        </form>

      <?php else: ?>
        <!-- 2FA DESACTIVADO / CONFIGURACIÓN -->
        <form action="" method="POST" autocomplete="off">
          <div class="card mb-3">
            <div class="card-header py-3 d-flex align-items-center">
              <h6 class="card-title mb-0 fw-bold text-uppercase">
                <i class="fa-solid fa-shield-xmark me-2 text-warning"></i><?= __("Configurar Autenticación en Dos Pasos (2FA)") ?>
              </h6>
            </div>

            <div class="card-body">
              <div class="alert alert-warning border-0 bg-warning-subtle mb-4 d-flex align-items-center gap-3 p-3" role="alert">
                <div class="bg-body p-2 rounded-3 border border-warning-subtle">
                  <i class="fa-solid fa-triangle-exclamation text-warning fs-4"></i>
                </div>
                <div>
                  <strong class="d-block mb-1"><?= __("Aumenta la seguridad de tu cuenta") ?></strong>
                  <div class="small text-body-secondary">
                    <?= __("Te recomendamos activar la autenticación de doble factor para evitar accesos no autorizados a tu cuenta.") ?>
                  </div>
                </div>
              </div>

              <div class="row g-3">
                <!-- PASO 1: QR -->
                <div class="col-lg-6">
                  <h6 class="fw-bold mb-3"><?= __("1. Escanea este código QR") ?></h6>
                  <p class="text-muted small mb-3">
                    <?= __("Abre tu aplicación de autenticación (Google Authenticator, Authy o Microsoft Authenticator) y escanea este código QR:") ?>
                  </p>
                  <div class="text-center mb-3">
                    <div class="p-3 bg-white border rounded-3 d-inline-block">
                      <canvas id="qr-code-canvas"></canvas>
                    </div>
                  </div>
                  <div class="bg-body-secondary p-3 rounded text-center small mb-3">
                    <div class="text-muted mb-1"><?= __("¿No puedes escanear el código QR? Usa esta clave:") ?></div>
                    <code class="fw-bold fs-6 text-primary"><?= clear_html($temp_secret) ?></code>
                  </div>
                </div>

                <!-- PASO 2: CONFIRMACIÓN -->
                <div class="col-lg-6">
                  <h6 class="fw-bold mb-3"><?= __("2. Ingresa el código de verificación") ?></h6>
                  <p class="text-muted small mb-3">
                    <?= __("Una vez escaneado el código QR, ingresa a continuación el código de 6 dígitos generado por tu aplicación para verificar la sincronización:") ?>
                  </p>

                  <input type="hidden" name="2fa_code" id="2fa_code_hidden">
                  
                  <div class="d-flex gap-2 mb-4 justify-content-center">
                    <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" style="width: 40px; height: 40px" required />
                    <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" style="width: 40px; height: 40px" required />
                    <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" style="width: 40px; height: 40px" required />
                    <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" style="width: 40px; height: 40px" required />
                    <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" style="width: 40px; height: 40px" required />
                    <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" style="width: 40px; height: 40px" required />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- BOTONERA DE ACTIVACIÓN -->
          <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom mt-3">
            <a href="<?= front_route("account/profile") ?>" class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
              <i class="fa-solid fa-arrow-left me-2"></i> <?= __("Cancelar") ?>
            </a>
            <button name="enable_2fa" type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
              <i class="fa-solid fa-shield-check me-2"></i> <?= __("Activar 2FA") ?>
            </button>
          </div>
        </form>
      <?php endif; ?>

    </div>
  </div>
</div>
