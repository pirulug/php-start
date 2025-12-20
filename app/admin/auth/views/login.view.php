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
            <label class="form-label text-muted small text-uppercase fw-bold ps-1">Usuario</label>
            <div class="input-group">
              <span class="input-group-text border-0 bg-body ps-3 text-secondary">
                <i class="fa-solid fa-user"></i>
              </span>
              <input class="form-control border-0 bg-body py-2" type="text" name="user-name" placeholder="Ej: admin"
                required autofocus autocomplete="username">
            </div>
          </div>

          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label text-muted small text-uppercase fw-bold ps-1 mb-0">Contraseña</label>
              <!-- <a href="#" class="text-decoration-none small text-primary fw-bold" tabindex="-1">¿Olvidaste la clave?</a> -->
            </div>

            <div class="input-group">
              <span class="input-group-text border-0 bg-body ps-3 text-secondary">
                <i class="fa-solid fa-lock"></i>
              </span>
              <input class="form-control border-0 bg-body py-2" id="inputChoosePassword" type="password"
                name="user-password" placeholder="••••••••" required autocomplete="current-password">

              <button class="btn border-0 bg-body text-secondary pe-3" id="togglePassword" type="button">
                <i class="fa-solid fa-eye-slash"></i>
              </button>
            </div>
          </div>

          <div class="mb-4 form-check ms-1">
            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember-me" value="true" checked>
            <label class="form-check-label text-muted small user-select-none" for="rememberMe">
              Mantener sesión iniciada
            </label>
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-primary py-2 fw-bold rounded-3" type="submit">
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
          <a href="<?= SITE_URL ?>" class="btn btn-sm btn-link text-muted text-decoration-none">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver al sitio
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  const toggleBtn = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("inputChoosePassword");
  const icon = toggleBtn.querySelector("i");

  toggleBtn.addEventListener("click", () => {
    // Alternar tipo
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);

    // Alternar icono
    icon.classList.toggle("fa-eye");
    icon.classList.toggle("fa-eye-slash");

    // Asegurar foco para seguir escribiendo
    passwordInput.focus();
  });
</script>