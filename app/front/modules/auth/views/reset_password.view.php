<?php start_block('title'); ?>
Nueva Contraseña
<?php end_block(); ?>

<?php start_block('css'); ?>
<?= static_libs_css("sweetalert2", "sweetalert2.css") ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= static_libs_js("sweetalert2", "sweetalert2.js") ?>
<?php end_block(); ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">

      <div class="card">
        <div class="card-body p-3 p-md-4">

          <div class="text-center mb-4">
            <div
              class="d-inline-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle mb-3"
              style="width: 70px; height: 70px;">
              <i class="fa-solid fa-user-shield fs-2"></i>
            </div>
            <h4 class="fw-bold text-uppercase">Nueva Contraseña</h4>
            <p class="text-body-secondary small mb-0">Hola <strong><?= $user->user_login ?></strong>, estás a un paso de
              recuperar tu cuenta.</p>
          </div>

          <form action="" method="POST" class="mt-4">
            <div class="mb-4">
              <label for="password" class="form-label">Escribe tu nueva contraseña</label>
              <div class="input-group">
                <input type="password" name="password" id="password" class="form-control"
                  placeholder="Mínimo 6 caracteres" required minlength="6">
                <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password="">
                  <i class="bi-regular bi-eye"></i>
                </button>
              </div>
              <div class="form-text small mt-2">Asegúrate de usar una combinación segura de caracteres.</div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary btn-lg py-3 text-uppercase small fw-bold">
                <i class="fa-solid fa-floppy-disk me-2"></i> Establecer Contraseña
              </button>
            </div>

            <div class="text-center mt-4">
              <a href="<?= front_route("signin") ?>" class="text-decoration-none small fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al Inicio
              </a>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>