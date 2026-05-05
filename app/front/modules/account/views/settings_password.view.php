<?php start_block("title") ?>
Seguridad de la Cuenta
<?php end_block() ?>

<div class="container my-3">
  <div class="row g-3">

    <!-- SIDEBAR DE NAVEGACIÓN -->
    <div class="col-md-4 col-lg-3">
      <?php require_once BASE_DIR . '/app/front/modules/account/views/partials/sidebar.php'; ?>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="col-md-8 col-lg-9">
      <form action="" method="POST">
        <div class="card mb-3">
          <div class="card-header py-3">
            <h6 class="card-title mb-0 fw-bold text-uppercase">
              <i class="fa-solid fa-lock text-danger me-2"></i>Seguridad de Acceso
            </h6>
          </div>

          <div class="card-body">
            <div
              class="alert alert-warning border-0 bg-warning-subtle p-3 rounded mb-3 d-flex gap-3 align-items-center">
              <div
                class="bg-body p-2 rounded-3 border border-warning-subtle d-flex align-items-center justify-content-center"
                style="width: 45px; height: 45px;">
                <i class="fa-solid fa-shield-exclamation text-warning fs-4"></i>
              </div>
              <div>
                <strong class="d-block mb-1 text-body">Protege tu cuenta</strong>
                <p class="mb-0 small text-body-secondary">Tu contraseña debe ser difícil de adivinar. Recomendamos una
                  mezcla de letras, números y símbolos.</p>
              </div>
            </div>

            <!-- CONTRASEÑA ACTUAL -->
            <div class="mb-3">
              <label for="current_password" class="form-label">Contraseña Actual</label>
              <div class="input-group">
                <input type="password" id="current_password" name="current_password" class="form-control"
                  placeholder="••••••••••••" required>
                <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                  <i class="bi-regular bi-eye"></i>
                </button>
              </div>
              <div class="form-text small mt-1">Obligatorio para verificar tu identidad antes del cambio.</div>
            </div>

            <hr class="my-3 opacity-10">

            <!-- NUEVA CONTRASEÑA -->
            <div class="row g-3">
              <div class="col-md-6">
                <label for="password" class="form-label">Nueva Contraseña</label>
                <div class="input-group">
                  <input type="password" id="password" name="password" class="form-control" placeholder="Nueva clave"
                    required>
                  <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                    <i class="bi-regular bi-eye"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <div class="input-group">
                  <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                    placeholder="Repite clave" required>
                  <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                    <i class="bi-regular bi-eye"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- BOTONERA STICKY -->
        <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom mt-3">
          <a href="<?= front_route("account/profile") ?>"
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
</div>