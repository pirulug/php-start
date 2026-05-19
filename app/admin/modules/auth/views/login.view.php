<div class="container d-flex flex-column justify-content-center vh-100">
  <div class="row">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-4 mx-auto">

      <div class="text-center mb-4">
        <div
          class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3"
          style="width: 60px; height: 60px;">
          <i class="fa-solid fa-layer-group fa-xl"></i>
        </div>
        <h3 class="fw-bold mb-1">Bienvenido de nuevo</h3>
        <p class="text-muted small">Ingresa a tu panel de control</p>
      </div>

      <div class="p-4 p-md-5 rounded-4 bg-primary bg-opacity-10">

        <?= $notifier->showBootstrap(); ?>

        <form method="post" autocomplete="on">

          <div class="mb-3">
            <label class="form-label" for="user-name">Usuario</label>
            <input class="form-control bg-body" type="text" name="user-name" id="user-name" placeholder="Ej: admin" required autofocus autocomplete="username">
          </div>

          <div class="mb-3">
            <label class="form-label" for="user-password">Contraseña</label>
            <input class="form-control" id="user-password" type="password" name="user-password" placeholder="••••••••" required autocomplete="current-password" data-pr-toggle-password>
          </div>

          <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember-me" value="true" checked>
            <label class="form-check-label text-muted small user-select-none" for="rememberMe">
              Mantener sesión iniciada
            </label>
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-primary py-2 fw-bold rounded-3 text-uppercase" type="submit">
              <i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar Sesión
            </button>
          </div>

        </form>
      </div>

      <div class="text-center mt-4">
        <!-- <p class="text-muted small">
          ¿No tienes una cuenta? <a href="#" class="text-primary fw-bold text-decoration-none">Contáctanos</a>
    </p> -->
        <div class="mt-3">
          <a href="<?= APP_URL ?>" class="btn btn-sm btn-link text-muted text-decoration-none">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver al sitio
          </a>
        </div>
      </div>

    </div>
  </div>
</div>