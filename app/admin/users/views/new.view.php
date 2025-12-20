<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropimg.css" ?>">

<form id="formNewUser" enctype="multipart/form-data" action="" method="post" autocomplete="off">

  <div class="row g-4">

    <div class="col-12 col-lg-8">
      <div class="card h-100">

        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-user-plus text-primary"></i>
            Información de Cuenta
          </h5>
        </div>

        <div class="card-body">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label text-muted small text-uppercase fw-bold">Usuario <span
                  class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="user_login" class="form-control" placeholder="Ej: jdoe"
                  value="<?= isset($_POST['user_login']) ? htmlspecialchars($_POST['user_login']) : '' ?>" required>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label text-muted small text-uppercase fw-bold">Email <span
                  class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" name="user_email" class="form-control" placeholder="nombre@correo.com"
                  value="<?= isset($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : '' ?>" required>
              </div>
            </div>

            <div class="col-12">
              <label class="form-label text-muted small text-uppercase fw-bold">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-key"></i></span>
                <input class="form-control" type="password" name="user_password" id="user_password"
                  placeholder="••••••••"
                  value="<?= isset($_POST['user_password']) ? htmlspecialchars($_POST['user_password']) : '' ?>">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                  <i class="fa-solid fa-eye" id="toggleIcon"></i>
                </button>
              </div>
              <div class="form-text">Dejar en blanco para generar una automáticamente o enviar enlace.</div>
            </div>

            <div class="col-12">
              <label class="form-label text-muted small text-uppercase fw-bold">Rol de Usuario <span
                  class="text-danger">*</span></label>
              <?php if ($roles): ?>
                <div class="input-group">
                  <span class="input-group-text bg-transparent"><i class="fa-solid fa-shield-halved"></i></span>
                  <select class="form-select" name="role_id" required>
                    <option value="">Seleccionar un rol...</option>
                    <?php foreach ($roles as $role): ?>
                      <option value="<?= $role->role_id ?>" <?= isset($_POST['role_id']) && $_POST['role_id'] == $role->role_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role->role_name) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php else: ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i>
                  <div>No hay roles creados. <a href="roles.php" class="alert-link">Crear uno ahora</a>.</div>
                </div>
              <?php endif; ?>
            </div>

            <div>
              <label class="form-label text-muted small text-uppercase fw-bold">Estado de la cuenta</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-power-off"></i></span>
                <select class="form-select" name="user_status" required>
                  <option value="1" <?= isset($_POST['user_status']) && $_POST['user_status'] == 1 ? 'selected' : '' ?>>
                    🟢 Activo
                  </option>
                  <option value="0" <?= isset($_POST['user_status']) && $_POST['user_status'] == 2 ? 'selected' : '' ?>
                    <?= !isset($_POST['user_status']) ? 'selected' : '' ?>> 🔴 Inactivo / Pendiente
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
            <i class="fa-solid fa-id-badge text-success"></i>
            Perfil y Estado
          </h5>
        </div>

        <div class="card-body d-flex flex-column gap-4">

          <div>
            <label class="form-label text-muted small text-uppercase fw-bold mb-2">Imagen de Perfil</label>

            <div
              class="p-4 rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center flex-column">
              <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
                accept=".jpg,.jpeg,.png,.gif,.webp">
              <!-- <small class="text-muted mt-2 text-center" style="font-size: 0.75rem;">
                Formatos: JPG, PNG, WEBP
              </small> -->
            </div>
          </div>



        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="d-flex justify-content-end gap-2 py-3">
        <a href="<?= SITE_URL ?>/panel/users" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>
        <button class="btn btn-primary px-5" type="submit" name="save">
          <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Usuario
        </button>
      </div>
    </div>

  </div>
</form>

<script src="<?= SITE_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  // Inicializar plugin de imagen
  DropImg.init();

  // Función para ver/ocultar contraseña
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