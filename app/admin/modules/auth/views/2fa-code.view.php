<?php block_start("title") ?>
2fa-code
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
    <i class="bi bi-shield-lock"></i>
  </div>
  <h5 class="fw-bold mb-1"><?= __("Verificación en dos pasos") ?></h5>
  <p class="text-body-secondary small">
    <?= __("Ingresa el código generado por tu aplicación Google Authenticator") ?>
  </p>
</div>
<div class="card auth-card border-0 rounded-3">
  <div class="card-body p-4">
    <form action="<?= admin_route("dashboard") ?>">
      <div class="mb-4 text-center">
        <p class="text-body-secondary small mb-4">
          <?= __("Por favor ingresa el código de verificación de 6 dígitos abajo.") ?>
        </p>
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
      </div>
      <div class="d-grid mb-3">
        <button class="btn btn-primary" type="submit"><?= __("Confirmar e Iniciar Sesión") ?></button>
      </div>
      <div class="text-center">
        <a class="small text-decoration-none auth-footer-link" href="#">
          <?= __("¿Perdiste tu dispositivo? Usa el código de respaldo") ?>
        </a>
      </div>
    </form>
  </div>
</div>
<div class="text-center mt-3">
  <a class="small text-decoration-none auth-footer-link" href="<?= admin_route("sign-in") ?>">
    <?= __("Volver al Inicio de Sesión") ?>
  </a>
</div>