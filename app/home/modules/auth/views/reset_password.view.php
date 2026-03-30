<?php start_block('title'); ?>
Nueva Contraseña
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
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 60px; height: 60px;">
              <i class="fa-solid fa-user-shield fs-3"></i>
            </div>
            <h3 class="fw-bold">Crea tu nueva contraseña</h3>
            <p class="text-muted">Hola <strong><?= $user->user_login ?></strong>, estás a un paso de recuperar el acceso a tu cuenta.</p>
          </div>

          <?= $notifier->showBootstrap(); ?>

          <form action="" method="POST" class="mt-4">
            <div class="mb-4">
              <label for="password" class="form-label fw-semibold small text-muted text-uppercase">Nueva Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-muted">
                  <i class="fa-solid fa-lock"></i>
                </span>
                <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="Mínimo 6 caracteres" required minlength="6">
                <button class="btn border border-start-0 toggle-password" type="button" tabindex="-1">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="mb-4">
              <label for="confirm_password" class="form-label fw-semibold small text-muted text-uppercase">Confirmar Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-muted">
                  <i class="fa-solid fa-shield-check"></i>
                </span>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control border-start-0 ps-0" placeholder="Repite tu contraseña" required minlength="6">
              </div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg rounded-3 py-3 fw-bold">
                <i class="fa-solid fa-check-circle me-2"></i> Cambiar Contraseña e Iniciar Sesión
              </button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<script>
  // Script para mostrar/ocultar contraseña
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
      const input = this.previousElementSibling;
      const icon = this.querySelector('i');
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  });
</script>

<?php start_block('js'); ?>
<script src="<?= APP_URL ?>/static/plugins/sweetalert2/sweetalert2.js"></script>
<?php end_block(); ?>

