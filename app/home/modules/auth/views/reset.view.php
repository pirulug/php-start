<?php start_block('title'); ?>
Recuperar Contraseña
<?php end_block(); ?>

<?php start_block('css'); ?>
<link rel="stylesheet" href="<?= APP_URL ?>/static/plugins/sweetalert2/sweetalert2.css">
<?php end_block(); ?>

<div class="container my-3">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      
      <div class="card   rounded">
        <div class="card-body p-4 p-md-5">
          
          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
              <i class="fa-solid fa-key fs-3"></i>
            </div>
            <h3 class="fw-bold">¿Olvidaste tu contraseña?</h3>
            <p class="text-muted">Introduce tu correo electrónico y te enviaremos un enlace para que vuelvas a entrar en tu cuenta.</p>
          </div>

          <?= $notifier->showBootstrap(); ?>

          <form id="reset_password_form" action="" method="POST" class="mt-4">
            <div class="mb-4">
              <label for="email" class="form-label fw-semibold small text-muted text-uppercase">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-muted">
                  <i class="fa-regular fa-envelope"></i>
                </span>
                <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="ejemplo@correo.com" required>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" id="btn_submit" class="btn btn-primary btn-lg rounded-3 py-3 fw-bold">
                <span class="btn-text"><i class="fa-solid fa-paper-plane me-2"></i> Enviar Enlace</span>
                <span class="btn-loading d-none">
                  <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                  Enviando...
                </span>
              </button>
            </div>

            <div class="text-center mt-4">
              <a href="<?= home_route("signin") ?>" class="text-decoration-none small fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al Inicio de Sesión
              </a>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('reset_password_form');
  const btn = document.getElementById('btn_submit');
  const btnText = btn.querySelector('.btn-text');
  const btnLoading = btn.querySelector('.btn-loading');

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Estado de carga
    btn.disabled = true;
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');

    const formData = new FormData(form);

    fetch('<?= APP_URL ?>/ajax/auth/reset', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: '¡Enviado!',
          text: data.message,
          confirmButtonColor: '#0d6efd'
        }).then(() => {
          window.location.href = '<?= home_route("signin") ?>';
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message,
          confirmButtonColor: '#0d6efd'
        });
        // Restaurar botón si hay error
        btn.disabled = false;
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un error al procesar tu solicitud. Inténtalo de nuevo.',
        confirmButtonColor: '#0d6efd'
      });
      // Restaurar botón
      btn.disabled = false;
      btnText.classList.remove('d-none');
      btnLoading.classList.add('d-none');
    });
  });
});
</script>

<?php start_block('js'); ?>
<script src="<?= APP_URL ?>/static/plugins/sweetalert2/sweetalert2.js"></script>
<?php end_block(); ?>