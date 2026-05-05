<?php start_block('title'); ?>
Crear Cuenta
<?php end_block(); ?>

<div class="container my-3">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

      <div class="card">
        <div class="card-body p-3 p-md-4">

          <div class="text-center mb-4">
            <div
              class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3"
              style="width: 70px; height: 70px;">
              <i class="fa-solid fa-user-plus fs-2"></i>
            </div>
            <h4 class="fw-bold text-uppercase">Únete a nosotros</h4>
            <p class="text-body-secondary small mb-0">Crea tu cuenta en pocos pasos y comienza ahora</p>
          </div>

          <form action="" method="POST" class="mt-4">

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="user_name" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                <input type="text" name="username" id="user_name" class="form-control" placeholder="Ej: juan_perez"
                  required>
              </div>
              <div class="col-md-6">
                <label for="user_email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" name="email" id="user_email" class="form-control" placeholder="nombre@ejemplo.com"
                  required>
              </div>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="user_password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password" id="user_password" class="form-control" placeholder="••••••••"
                    required minlength="6">
                  <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                    <i class="bi-regular bi-eye"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="re_user_password" class="form-label">Confirmar Clave <span
                    class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password_confirmation" id="re_user_password" class="form-control"
                    placeholder="••••••••" required minlength="6">
                  <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                    <i class="bi-regular bi-eye"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label small text-body-secondary" for="terms">
                  Acepto los <a href="#" class="text-decoration-none fw-bold">Términos y Condiciones</a> del servicio.
                </label>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-success btn-lg py-3 text-uppercase small fw-bold">
                <i class="fa-solid fa-rocket me-2"></i> Crear mi cuenta gratuita
              </button>
            </div>

            <div class="text-center mt-4">
              <p class="text-body-secondary small mb-0">¿Ya tienes una cuenta?
                <a href="<?= front_route("signin") ?>" class="text-decoration-none fw-bold">Inicia Sesión Aquí</a>
              </p>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>