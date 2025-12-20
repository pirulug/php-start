<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropimg.css" ?>">

<form enctype="multipart/form-data" method="post" autocomplete="off">

  <input type="hidden" name="user_id" value="<?= $cipher->encrypt($user->user_id) ?>">

  <div class="row g-4">

    <div class="col-12 col-lg-8">
      <div class="card h-100">

        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-user-pen text-primary"></i>
            Editar Cuenta
          </h5>
        </div>

        <div class="card-body">
          <div class="row g-3">

            <div class="col-12 col-md-6">
              <label class="form-label text-muted small text-uppercase fw-bold">Nombre</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="user_login" class="form-control"
                  value="<?= htmlspecialchars($user->user_login) ?>" required>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label text-muted small text-uppercase fw-bold">Email</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-envelope"></i></span>
                <input type="text" name="user_email" class="form-control"
                  value="<?= htmlspecialchars($user->user_email) ?>" required>
              </div>
            </div>

            <div class="col-12">
              <label class="form-label text-muted small text-uppercase fw-bold">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-key"></i></span>
                <input class="form-control" type="password" name="user_password" id="user_password"
                  value="<?= htmlspecialchars($cipher->decrypt($user->user_password)) ?>">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                  <i class="fa-solid fa-eye" id="toggleIcon"></i>
                </button>
              </div>
              <div class="form-text">Actualiza este campo solo si deseas cambiar la contraseña.</div>
            </div>

            <div class="col-12">
              <label class="form-label text-muted small text-uppercase fw-bold">Rol Asignado</label>
              <?php if ($roles): ?>
                <div class="input-group">
                  <span class="input-group-text bg-transparent"><i class="fa-solid fa-shield-halved"></i></span>
                  <select class="form-select" name="role_id" required>
                    <option value="">- Seleccionar -</option>
                    <?php foreach ($roles as $role): ?>
                      <option value="<?= $role->role_id ?>" <?= ((isset($_POST['role_id']) && $_POST['role_id'] == $role->role_id) ||
                          (!isset($_POST['role_id']) && isset($user) && $user->role_id == $role->role_id))
                          ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role->role_name) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php else: ?>
                <div class="alert alert-warning" role="alert">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i> No hay roles disponibles.
                </div>
              <?php endif; ?>
            </div>

            <div>
              <label class="form-label text-muted small text-uppercase fw-bold">Estado</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-power-off"></i></span>
                <select class="form-select" name="user_status" required>
                  <option value="1" <?= $user->user_status == 1 ? 'selected' : '' ?>>
                    🟢 Activo
                  </option>
                  <option value="2" <?= $user->user_status == 2 ? 'selected' : '' ?>>
                    🔴 Inactivo
                  </option>
                </select>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card h-100">

        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-image-portrait text-success"></i>
            Perfil
          </h5>
        </div>

        <div class="card-body d-flex flex-column gap-4">

          <div>
            <label class="form-label text-muted small text-uppercase fw-bold mb-2">Avatar</label>

            <div
              class="p-4 rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center flex-column">
              <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
                data-default="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>"
                accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="d-flex justify-content-end gap-2 py-3">
        <a href="<?= url_admin('users') ?>" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>
        <button class="btn btn-primary px-5" type="submit" name="save">
          <i class="fa-solid fa-rotate me-2"></i>Actualizar Usuario
        </button>
      </div>
    </div>

  </div>
</form>

<script src="<?= SITE_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();

  function togglePassword() {
    const input = document.getElementById('user_password');
    const icon = document.getElementById('toggleIcon');

    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = "password";
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>