<?php start_block("title") ?>
Seguridad - Configuración
<?php end_block() ?>

<div class="row g-4">

  <!-- SIDEBAR -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top" style="top: 1rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Ajustes</h6>
      </div>
      <div class="list-group list-group-flush">
        <a href="<?= admin_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false ? 'active' : '' ?>">
          <i class="fa-solid fa-user-circle fa-fw"></i>
          <span>Mi Perfil</span>
        </a>
        <a href="<?= admin_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'password') !== false ? 'active' : '' ?>">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span>Seguridad</span>
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="col-md-8 col-lg-9">
    <form action="" method="POST">
      <div class="card border-danger-subtle mb-4 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
          <h5 class="card-title mb-0 text-danger-emphasis d-flex align-items-center">
            <i class="fa-solid fa-shield-halved me-2"></i>Actualizar Contraseña
          </h5>
        </div>

        <div class="card-body">
          <div class="alert alert-light border mb-4 d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-info text-info me-3 fs-4"></i>
            <div>
              <strong>Consejo de seguridad:</strong>
              <div class="small text-muted">Asegúrate de no usar la misma contraseña en múltiples sitios.</div>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Contraseña Actual</label>
            <div class="input-group">
              <span class="input-group-text bg-body-tertiary"><i class="fa-solid fa-key text-muted"></i></span>
              <input type="password" name="current_password" class="form-control password-field" placeholder="••••••••••••" required>
              <button class="btn btn-outline-secondary toggle-password" type="button">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>

          <hr class="text-secondary opacity-25">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nueva Contraseña</label>
              <div class="input-group">
                <input type="password" name="password" class="form-control password-field" placeholder="Nueva contraseña" required>
                <button class="btn btn-outline-secondary toggle-password" type="button">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Confirmar Contraseña</label>
              <div class="input-group">
                <input type="password" name="confirm_password" class="form-control password-field" placeholder="Repite la nueva contraseña" required>
                <button class="btn btn-outline-secondary toggle-password" type="button">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end py-3">
          <button name="change_password" type="submit" class="btn btn-danger px-4">
            <i class="fa-solid fa-check-double me-1"></i> Actualizar Contraseña
          </button>
        </div>
      </div>
    </form>
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
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });
</script>
