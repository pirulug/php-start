<?php block_start("title") ?>
2fa-qr
<?php block_end() ?>

<?php block_start("js"); ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.verification-code-input');
    inputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
      });
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });
  });
</script>
<?php block_end(); ?>


<div class="text-center mb-4">
  <div class="auth-logo-box d-inline-flex align-items-center justify-content-center text-white rounded-3 mb-3"
    style="width: 44px; height: 44px">
    <i class="bi bi-shield-check"></i>
  </div>
  <h5 class="fw-bold mb-1"><?= __("Verificación en dos pasos") ?></h5>
  <p class="text-body-secondary small">
    <?= __("Protege tu cuenta usando Google Authenticator") ?>
  </p>
</div>
<div class="card auth-card border-0 rounded-3">
  <div class="card-body p-4 text-center">
    <div class="mb-4">
      <p class="fw-semibold small text-start">
        <?= __("1. Escanea este código QR con Google Authenticator") ?>
      </p>
      <div class="p-3 bg-body-secondary rounded-3 d-inline-block mb-3">
        <!-- Crisp Vector QR Code SVG Mockup-->
        <svg class="text-body" width="150" height="150" viewBox="0 0 100 100" fill="currentColor">
          <path
            d="M0 0h35v35H0V0zm5 5v25h25V5H5zm50 0h35v35H55V0zm5 5v25h25V5H60zM0 55h35v35H0V55zm5 5v25h25V60H5zm45-5h10v10H50V55zm15 0h10v10H65V55zm10 0h10v10H75V55zm-25 15h10v10H50V70zm25 0h10v10H75V70zm-15 15h10v10H60V85zm15 0h10v10H75V85zm-25 0h10v10H50V85zM12 12h11v11H12V12zm55 0h11v11H67V12zM12 67h11v11H12V67z">
          </path>
        </svg>
      </div>
      <p class="text-body-secondary small mb-0">
        <?= __("¿No puedes escanear el código QR? Usa la clave de configuración:") ?>
        <code class="fw-bold bg-body-tertiary px-2 py-1 rounded text-primary small">
          JBSWY3DPEHPK3PXP
        </code>
      </p>
    </div>
    <hr class="border-secondary-subtle my-4" />
    <div class="text-start">
      <p class="fw-semibold small mb-3">
        <?= __("2. Ingresa el código de confirmación de 6 dígitos abajo") ?>
      </p>
      <form action="<?= admin_route("dashboard") ?>">
        <div class="d-flex gap-2 mb-4 justify-content-center">
          <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1"
            style="width: 50px; height: 50px" required />
          <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1"
            style="width: 50px; height: 50px" required />
          <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1"
            style="width: 50px; height: 50px" required />
          <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1"
            style="width: 50px; height: 50px" required />
          <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1"
            style="width: 50px; height: 50px" required />
          <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1"
            style="width: 50px; height: 50px" required />
        </div>
        <div class="d-grid">
          <button class="btn btn-primary" type="submit"><?= __("Verificar y Activar") ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="text-center mt-3">
  <a class="small text-decoration-none auth-footer-link" href="<?= admin_route("sign-in") ?>">
    <?= __("Volver al Inicio de Sesión") ?>
  </a>
</div>