<link rel="stylesheet" href="<?= APP_URL . "/static/plugins/dropzone/dropimg.css" ?>">

<div class="container mt-3">
<div class="row g-4">

  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top" style="top: 1rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Ajustes</h6>
      </div>
      <div class="list-group list-group-flush" role="tablist">
        <a class="list-group-item list-group-item-action active d-flex align-items-center gap-2 py-3"
          data-bs-toggle="list" href="#tab-account" role="tab">
          <i class="fa-solid fa-user-circle fa-fw"></i>
          <span>Mi Cuenta</span>
        </a>
        <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3" data-bs-toggle="list"
          href="#tab-security" role="tab">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span>Seguridad</span>
        </a>
      </div>
    </div>
  </div>

  <div class="col-md-8 col-lg-9">
    <div class="tab-content">

      <div class="tab-pane fade show active" id="tab-account" role="tabpanel">
        <form enctype="multipart/form-data" method="POST">
          <div class="card mb-4">

            <div class="card-header bg-transparent border-bottom py-3">
              <h5 class="card-title mb-0">Información del Perfil</h5>
            </div>

            <div class="card-body">

              <div class=" mb-4 pb-4 border-bottom">
                <div>
                  <h6 class="fw-bold mb-1">Foto de Perfil</h6>
                  <p class="text-muted small mb-0">Haz clic en la imagen para cambiarla.</p>
                </div>
                <div class="me-4">
                  <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
                    data-default="<?= APP_URL ?>/uploads/user/<?= $user->user_image ?>"
                    accept=".jpg,.jpeg,.png,.gif,.webp">

                </div>

              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small text-muted">Nombre de Usuario</label>
                  <div class="input-group">
                    <span class="input-group-text bg-body-tertiary"><i class="fa-solid fa-at text-muted"></i></span>
                    <input type="text" class="form-control bg-body-tertiary" value="<?= $user->user_login ?>" disabled
                      readonly>
                  </div>
                  <div class="form-text small">El nombre de usuario no se puede cambiar.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Correo Electrónico</label>
                  <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="user_email" class="form-control" value="<?= $user->user_email ?>"
                      required>
                  </div>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small text-muted">Nombre</label>
                  <input type="text" name="user_first_name" class="form-control" value="<?= $usermeta->first_name ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Apellidos</label>
                  <input type="text" name="user_last_name" class="form-control" value="<?= $usermeta->last_name ?>">
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small text-muted">Alias <span class="text-danger">*</span></label>
                  <input type="text" name="user_nickname" class="form-control" value="<?= $user->user_nickname ?>"
                    required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted">Mostrar públicamente como</label>
                  <select name="user_display_name" class="form-select">
                    <?php
                    $display_options = [
                      $user->user_login,
                      $user->user_nickname,
                      $usermeta->first_name,
                      $usermeta->last_name,
                      trim($usermeta->first_name . ' ' . $usermeta->last_name),
                      trim($usermeta->last_name . ' ' . $usermeta->first_name),
                    ];
                    $display_options = array_unique(array_filter($display_options));
                    foreach ($display_options as $option):
                      $selected = ($option === $user->user_display_name) ? 'selected' : '';
                      echo "<option value=\"" . htmlspecialchars($option, ENT_QUOTES) . "\" $selected>$option</option>";
                    endforeach;
                    ?>
                  </select>
                </div>
              </div>

              <input type="hidden" name="id" value="<?= $user->user_id ?>">
            </div>

            <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end py-3">
              <button name="update_profile" type="submit" class="btn btn-primary px-4">
                <i class="fa-solid fa-save me-1"></i> Guardar Cambios
              </button>
            </div>

          </div>
        </form>
      </div>

      <div class="tab-pane fade" id="tab-security" role="tabpanel">
        <form action="" method="POST">
          <div class="card border-danger-subtle mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
              <h5 class="card-title mb-0 text-danger-emphasis">
                <i class="fa-solid fa-lock me-2"></i>Contraseña y Seguridad
              </h5>
            </div>

            <div class="card-body">
              <div class="alert alert-light border mb-4 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-info text-info me-3 fs-4"></i>
                <div>
                  <strong>Consejo de seguridad:</strong>
                  <div class="small text-muted">Usa una contraseña fuerte que no uses en otros sitios.</div>
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Contraseña Actual</label>
                <div class="input-group">
                  <span class="input-group-text bg-body-tertiary"><i class="fa-solid fa-key text-muted"></i></span>
                  <input type="password" name="current_password" class="form-control password-field"
                    placeholder="••••••••••••">
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
                    <input type="password" name="password" class="form-control password-field"
                      placeholder="Nueva contraseña">
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                      <i class="fa-regular fa-eye"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Confirmar Contraseña</label>
                  <div class="input-group">
                    <input type="password" name="confirm_password" class="form-control password-field"
                      placeholder="Repite la nueva contraseña">
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
  </div>
</div>
</div>

<script src="<?= APP_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();

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