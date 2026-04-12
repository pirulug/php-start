<?php start_block("title") ?>
Seguridad de la Cuenta
<?php end_block() ?>

<div class="container my-3">
<div class="row g-3 mt-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top bg-body" style="top: 2rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Área Personal</h6>
      </div>
      <div class="list-group list-group-flush bg-transparent">
        <a href="<?= home_route("account/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-id-card fa-fw"></i>
          <span class="">Vista General</span>
        </a>
        <a href="<?= home_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-circle-user fa-fw"></i>
          <span class="">Ajustes de Perfil</span>
        </a>
        <a href="<?= home_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3 <?= strpos($_SERVER['REQUEST_URI'], 'password') !== false ? 'active' : '' ?>">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span class="fw-bold ">Privacidad y Seguridad</span>
        </a>
        <a href="<?= home_route("account/settings/api") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-key fa-fw"></i>
          <span class="">Conexiones API</span>
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
            <i class="fa-solid fa-lock text-danger me-2"></i>Seguridad de Acceso
          </h5>
        </div>

        <div class="card-body p-3 p-md-4">
          
          <div class="alert alert-warning border bg-body p-3 rounded mb-3 d-flex gap-3 align-items-center">
            <div class="bg-warning p-2 border">
              <i class="fa-solid fa-shield-exclamation text-body-secondary fs-4"></i>
            </div>
            <div>
              <strong class="d-block mb-1 text-body">Protege tu cuenta</strong>
              <p class="mb-0 small text-body-secondary">Tu contraseña debe ser difícil de adivinar. Recomendamos una mezcla de letras, números y símbolos.</p>
            </div>
          </div>

          <!-- CONTRASEÑA ACTUAL -->
          <div class="mb-3">
            <label class="form-label text-uppercase  fw-bold text-body-secondary">Ingresa tu Contraseña Actual</label>
            <div class="input-group">
              <span class="input-group-text bg-body border-end-0"><i class="fa-solid fa-key text-body-secondary"></i></span>
              <input type="password" name="current_password" class="form-control border-start-0" placeholder="••••••••••••" required>
              <button class="btn btn-outline-secondary border-start-0 toggle-password" type="button">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
            <div class="form-text  mt-2">Obligatorio para verificar tu identidad antes del cambio.</div>
          </div>

          <hr class="my-3 opacity-10">

          <!-- NUEVA CONTRASEÑA -->
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-uppercase  fw-bold text-body-secondary">Escribe la Nueva Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-lock-open text-body-secondary opacity-50"></i></span>
                <input type="password" name="password" class="form-control border-start-0 rounded-0 px-2" placeholder="Nueva clave" required>
                <button class="btn btn-outline-secondary border-start-0 rounded-0 toggle-password" type="button">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-uppercase  fw-bold text-body-secondary">Confirma la Nueva Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-check-double text-body-secondary opacity-50"></i></span>
                <input type="password" name="confirm_password" class="form-control border-start-0 rounded-0 px-2" placeholder="Repite clave" required>
                <button class="btn btn-outline-secondary border-start-0 rounded-0 toggle-password" type="button">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
          <button name="change_password" type="submit" class="btn btn-danger px-5 text-uppercase small fw-bold">
            <i class="fa-solid fa-save me-2"></i> Actualizar Contraseña
          </button>
        </div>
      </div>
    </form>
  </div>

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
