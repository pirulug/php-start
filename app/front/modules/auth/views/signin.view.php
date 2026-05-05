<?php start_block('title'); ?>
Iniciar Sesión
<?php end_block(); ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">

      <div class="card">
        <div class="card-body p-3 p-md-4">

          <div class="text-center mb-4">
            <div
              class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3"
              style="width: 70px; height: 70px;">
              <i class="fa-solid fa-arrow-right-to-bracket fs-2"></i>
            </div>
            <h4 class="fw-bold text-uppercase">Bienvenido</h4>
            <p class="text-body-secondary small mb-0">Introduce tus credenciales para acceder</p>
          </div>

          <form action="" method="POST" class="mt-4">
            <div class="mb-3">
              <label for="user_login" class="form-label">Usuario o Correo</label>
              <input type="text" name="login" id="user_login" class="form-control" placeholder="nombre@ejemplo.com"
                required>
            </div>

            <div class="mb-3">
              <label for="user_password" class="form-label">Contraseña</label>
              <div class="input-group mb-2">
                <input type="password" name="password" id="user_password" class="form-control" placeholder="••••••••"
                  required>
                <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                  <i class="bi-regular bi-eye"></i>
                </button>
              </div>
              <div class="text-end">
                <a href="<?= front_route("reset-password") ?>" class="text-decoration-none small fw-bold">¿Olvidaste tu
                  clave?</a>
              </div>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label small text-body-secondary" for="remember_me">Recordarme en este
                  equipo</label>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary btn-lg py-3 text-uppercase small fw-bold">
                <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Entrar ahora
              </button>
            </div>

            <div class="text-center mt-4">
              <p class="text-body-secondary small mb-0">¿Aún no tienes cuenta?
                <a href="<?= front_route("signup") ?>" class="text-decoration-none fw-bold">Crea una cuenta aquí</a>
              </p>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>