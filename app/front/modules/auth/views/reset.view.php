<?php start_block('title'); ?>
Recuperar Contraseña
<?php end_block(); ?>

<?php start_block('css'); ?>
<?= static_libs_css("sweetalert2", "sweetalert2.css") ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= static_libs_js("sweetalert2", "sweetalert2.js") ?>
<script src="<?= url_script_front("auth", "reset") ?>"></script>
<?php end_block(); ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">

      <div class="card">
        <div class="card-body p-3 p-md-4">

          <div class="text-center mb-4">
            <div
              class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle mb-3"
              style="width: 70px; height: 70px;">
              <i class="fa-solid fa-key fs-2"></i>
            </div>
            <h4 class="fw-bold text-uppercase"><?php __("¿Olvidaste tu clave?") ?></h4>
            <p class="text-body-secondary small mb-0">
              <?php __("Introduce tu correo y te enviaremos un enlace de recuperación.") ?>
            </p>
          </div>

          <form id="reset_password_form" action="" method="POST" class="mt-4">
            <div class="mb-4">
              <label for="email" class="form-label"><?php __("Correo Electrónico") ?></label>
              <input type="email" name="email" id="email" class="form-control" placeholder="nombre@ejemplo.com"
                required>
              <div class="form-text small mt-2"><?php __("Te enviaremos las instrucciones de recuperación.") ?></div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" id="btn_submit" class="btn btn-primary btn-lg py-3 text-uppercase small fw-bold">
                <span class="btn-text">
                  <i class="fa-solid fa-paper-plane me-2"></i>
                  <?php __("Enviar Enlace") ?>
                </span>
                <span class="btn-loading d-none">
                  <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                  <?php __("Enviando...") ?>
                </span>
              </button>
            </div>

            <div class="text-center mt-4">
              <a href="<?= front_route("signin") ?>" class="text-decoration-none small fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i>
                <?php __("Volver al Inicio de Sesión") ?>
              </a>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>