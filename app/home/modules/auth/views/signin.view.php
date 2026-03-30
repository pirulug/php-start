<?php start_block('title'); ?>
Iniciar Sesión
<?php end_block(); ?>

<div class="container my-3">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

      <div class="card   rounded">
        <div class="card-body p-4 p-md-5">

          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
              <i class="fa-solid fa-arrow-right-to-bracket fs-3"></i>
            </div>
            <h3 class="fw-bold">Bienvenido</h3>
            <p class="text-muted">Introduce tus credenciales para acceder.</p>
          </div>

          <?= $notifier->showBootstrap(); ?>

          <form action="" method="POST" class="mt-4">
            <div class="mb-3">
              <label for="user_login" class="form-label fw-semibold small text-muted text-uppercase">Usuario o Correo</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-muted">
                  <i class="fa-solid fa-user"></i>
                </span>
                <input type="text" name="login" id="user_login" class="form-control border-start-0 ps-0" placeholder="Usuario o Correo" required>
              </div>
            </div>

            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center">
                <label for="user_password" class="form-label fw-semibold small text-muted text-uppercase mb-0">Contraseña</label>
                <a href="<?= home_route("reset-password") ?>" class="text-decoration-none small fw-bold">¿Olvidaste tu contraseña?</a>
              </div>
              <div class="input-group mt-2">
                <span class="input-group-text bg-transparent border-end-0 text-muted">
                  <i class="fa-solid fa-lock"></i>
                </span>
                <input type="password" name="password" id="user_password" class="form-control border-start-0 ps-0" placeholder="Contraseña" required>
                <button class="btn border border-start-0 toggle-password" type="button" tabindex="-1">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label small text-muted" for="remember_me">Recordarme en este equipo</label>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary btn-lg rounded-3 py-3 fw-bold ">
                <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Iniciar Sesión
              </button>
            </div>

            <div class="text-center mt-4">
              <p class="text-muted small mb-0">¿Aún no tienes cuenta? 
                <a href="<?= home_route("signup") ?>" class="text-decoration-none fw-bold">Regístrate Aquí</a>
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
