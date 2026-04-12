<?php start_block('title'); ?>
Seguridad y Contraseña
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Cuenta', 'link' => admin_route('account/profile')],
  ['label' => 'Seguridad']
]) ?>
<?php end_block(); ?>

<div class="row g-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top" style="top: 1rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Gestión de Cuenta</h6>
      </div>
      <div class="list-group list-group-flush">
        <a href="<?= admin_route("account/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-id-card fa-fw"></i>
          <span class="fs-7">Vista General</span>
        </a>
        <a href="<?= admin_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-user-circle fa-fw"></i>
          <span class="fs-7">Información del Perfil</span>
        </a>
        <a href="<?= admin_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'password') !== false ? 'active' : '' ?>">
          <i class="fa-solid fa-shield-halved fa-fw text-primary"></i>
          <span class="fw-bold fs-7">Seguridad y Contraseña</span>
        </a>
        <a href="<?= admin_route("account/settings/api") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-key fa-fw"></i>
          <span class="fs-7">API Keys</span>
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    <form action="" method="POST">
      <div class="card mb-3">
        <div class="card-header bg-transparent border-bottom py-3">
          <h5 class="card-title mb-0 fs-6 fw-bold text-uppercase">
            <i class="fa-solid fa-lock me-2 text-danger"></i>Seguridad de la Cuenta
          </h5>
        </div>

        <div class="card-body p-3">
          <div class="alert alert-light border bg-body mb-3 d-flex align-items-center gap-3 p-3" role="alert">
            <div class="bg-body p-2 border">
               <i class="fa-solid fa-shield-check text-success fs-4"></i>
            </div>
            <div>
              <strong class="d-block mb-1">Mantén tu cuenta protegida</strong>
              <div class="small text-body-secondary">Te recomendamos usar una contraseña fuerte y única. No compartas tus credenciales con nadie.</div>
            </div>
          </div>

          <!-- CONTRASEÑA ACTUAL -->
          <div class="mb-3">
            <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Contraseña Actual</label>
            <div class="input-group">
              <span class="input-group-text bg-body border-end-0"><i class="fa-solid fa-key text-body-secondary"></i></span>
              <input type="password" name="current_password" class="form-control border-start-0" placeholder="••••••••••••" required>
              <button class="btn btn-outline-secondary border-start-0 toggle-password" type="button">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
            <div class="form-text x-small mt-2">Debes ingresar tu contraseña actual para autorizar el cambio.</div>
          </div>

          <hr class="my-3 opacity-10">

          <!-- NUEVA CONTRASEÑA -->
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Nueva Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-lock-open text-body-secondary"></i></span>
                <input type="password" name="password" class="form-control border-start-0" placeholder="Nueva contraseña" required>
                <button class="btn btn-outline-secondary border-start-0 toggle-password" type="button">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Confirmar Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-check-double text-body-secondary"></i></span>
                <input type="password" name="confirm_password" class="form-control border-start-0" placeholder="Misma contraseña" required>
                <button class="btn btn-outline-secondary border-start-0 toggle-password" type="button">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
          <button name="change_password" type="submit" class="btn btn-danger px-5 text-uppercase small fw-bold">
            <i class="fa-solid fa-shield-halved me-2"></i> Cambiar Contraseña
          </button>
        </div>
      </div>
    </form>
  </div>

</div>

<script>
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
      const input = this.closest('.input-group').querySelector('input');
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

<style>
  .fs-7 { font-size: 0.875rem; }
  .x-small { font-size: 0.75rem; }
</style>
