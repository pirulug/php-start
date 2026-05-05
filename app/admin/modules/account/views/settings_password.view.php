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
    <?php require_once BASE_DIR . '/app/admin/modules/account/views/partials/sidebar.php'; ?>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    <form action="" method="POST">
      <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
          <h6 class="card-title mb-0 fw-bold text-uppercase">
            <i class="fa-solid fa-lock me-2 text-danger"></i>Seguridad de la Cuenta
          </h6>
        </div>

        <div class="card-body">
          <div class="alert alert-info border-0 bg-info-subtle mb-3 d-flex align-items-center gap-3 p-3" role="alert">
            <div class="bg-body p-2 rounded-3 border border-info-subtle">
              <i class="fa-solid fa-shield-halved text-info fs-4"></i>
            </div>
            <div>
              <strong class="d-block mb-1">Mantén tu cuenta protegida</strong>
              <div class="small text-body-secondary">Te recomendamos usar una contraseña fuerte y única. No compartas
                tus credenciales con nadie.</div>
            </div>
          </div>

          <!-- CONTRASEÑA ACTUAL -->
          <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña Actual</label>
            <div class="input-group">
              <input type="password" id="current_password" name="current_password" class="form-control"
                placeholder="••••••••••••" required>
              <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
            <div class="form-text small mt-2">Debes ingresar tu contraseña actual para autorizar el cambio.</div>
          </div>

          <hr class="my-3 opacity-10">

          <!-- NUEVA CONTRASEÑA -->
          <div class="row g-3">
            <div class="col-md-6">
              <label for="password" class="form-label">Nueva Contraseña</label>
              <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Nueva contraseña"
                  required>
                <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-6">
              <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
              <div class="input-group">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                  placeholder="Misma contraseña" required>
                <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- BOTONERA STICKY -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom mt-3">
        <a href="<?= admin_route("account/profile") ?>"
          class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
          <i class="fa-solid fa-arrow-left me-2"></i> Cancelar
        </a>
        <button name="change_password" type="submit" class="btn btn-danger px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Contraseña
        </button>
      </div>
    </form>
  </div>

</div>