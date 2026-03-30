<?php start_block('title'); ?>
Crear Cuenta
<?php end_block(); ?>

<div class="container my-3">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">

      <div class="card   rounded">
        <div class="card-body p-4 p-md-5">

          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 60px; height: 60px;">
              <i class="fa-solid fa-user-plus fs-3"></i>
            </div>
            <h3 class="fw-bold">Únete a nosotros</h3>
            <p class="text-muted">Crea tu cuenta en pocos pasos.</p>
          </div>

          <?= $notifier->showBootstrap(); ?>

          <form action="" method="POST" class="mt-4">
            
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="user_name" class="form-label fw-semibold small text-muted text-uppercase">Nombre de Usuario</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0 text-muted">
                    <i class="fa-solid fa-at"></i>
                  </span>
                  <input type="text" name="username" id="user_name" class="form-control border-start-0 ps-0" placeholder="Usuario" required>
                </div>
              </div>
              <div class="col-md-6">
                <label for="user_email" class="form-label fw-semibold small text-muted text-uppercase">Correo Electrónico</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0 text-muted">
                    <i class="fa-regular fa-envelope"></i>
                  </span>
                  <input type="email" name="email" id="user_email" class="form-control border-start-0 ps-0" placeholder="ejemplo@mail.com" required>
                </div>
              </div>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="user_password" class="form-label fw-semibold small text-muted text-uppercase">Contraseña</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0 text-muted">
                    <i class="fa-solid fa-lock"></i>
                  </span>
                  <input type="password" name="password" id="user_password" class="form-control border-start-0 ps-0" placeholder="••••••••" required minlength="6">
                  <button class="btn border border-start-0 toggle-password" type="button" tabindex="-1">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="re_user_password" class="form-label fw-semibold small text-muted text-uppercase">Confirmar</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0 text-muted">
                    <i class="fa-solid fa-shield-check"></i>
                  </span>
                  <input type="password" name="password_confirmation" id="re_user_password" class="form-control border-start-0 ps-0" placeholder="••••••••" required minlength="6">
                </div>
              </div>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label small text-muted" for="terms">
                  Acepto los <a href="#" class="text-decoration-none fw-bold">Términos y Condiciones</a>
                </label>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-success btn-lg rounded-3 py-3 fw-bold ">
                <i class="fa-solid fa-rocket me-2"></i> Crear mi cuenta
              </button>
            </div>

            <div class="text-center mt-4">
              <p class="text-muted small mb-0">¿Ya tienes una cuenta? 
                <a href="<?= home_route("signin") ?>" class="text-decoration-none fw-bold">Inicia Sesión Aquí</a>
              </p>
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
