<?php block_start("title") ?>
Verificación en dos pasos
<?php block_end() ?>

<?php block_start("js"); ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.verification-code-input');
    const hiddenInput = document.getElementById('2fa_code_hidden');
    const form = hiddenInput.closest('form');

    inputs.forEach((input, index) => {
      // Enfocar el primer input al cargar
      if (index === 0) {
        input.focus();
      }

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

      // Auto enviar al completar los 6 dígitos
      if (code.length === 6) {
        form.submit();
      }
    }
  });
</script>
<?php block_end(); ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      
      <div class="text-center mb-4">
        <div class="bg-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
          style="width: 48px; height: 48px; font-size: 20px;">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h5 class="fw-bold mb-1"><?= __("Verificación en dos pasos") ?></h5>
        <p class="text-body-secondary small">
          <?= __("Ingresa el código generado por tu aplicación Google Authenticator") ?>
        </p>
      </div>

      <div class="card border-0 rounded-3">
        <div class="card-body p-4">

          <form method="post" autocomplete="off">
            <input type="hidden" name="2fa_code" id="2fa_code_hidden">
            <div class="mb-4 text-center">
              <p class="text-body-secondary small mb-4">
                <?= __("Por favor ingresa el código de verificación de 6 dígitos abajo.") ?>
              </p>
              <div class="d-flex gap-2 mb-4 justify-content-center">
                <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric"
                  style="width: 44px; height: 44px" required />
                <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric"
                  style="width: 44px; height: 44px" required />
                <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric"
                  style="width: 44px; height: 44px" required />
                <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric"
                  style="width: 44px; height: 44px" required />
                <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric"
                  style="width: 44px; height: 44px" required />
                <input class="form-control text-center fw-bold fs-4 p-2 verification-code-input" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric"
                  style="width: 44px; height: 44px" required />
              </div>
            </div>
            <div class="d-grid mb-3">
              <button class="btn btn-primary text-uppercase fw-bold" type="submit"><?= __("Confirmar e Iniciar Sesión") ?></button>
            </div>
          </form>

        </div>
      </div>
      
      <div class="text-center mt-3">
        <a class="small text-decoration-none" href="<?= front_route("signin") ?>">
          <?= __("Volver al Inicio de Sesión") ?>
        </a>
      </div>

    </div>
  </div>
</div>
