<?php start_block('title'); ?>
Editar Usuario
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Usuarios', 'link' => admin_route('users')],
  ['label' => 'Editar']
]) ?>
<?php end_block(); ?>

<?php start_block('css'); ?>
<?= static_libs_css("dropzone", "dropimg.css") ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= static_libs_js("dropzone", "dropimg.js") ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof DropImg !== 'undefined') DropImg.init();
  });
</script>
<?php end_block(); ?>


<form enctype="multipart/form-data" method="post" autocomplete="off">

  <input type="hidden" name="user_id" value="<?= $cipher->encrypt($user->user_id) ?>">

  <div class="row g-3">

    <div class="col-12 col-lg-8">
      <div class="card mb-3">
        <div class="card-body">
          <div class="row g-3">

            <div class="col-12 col-md-6">
              <label for="user_login" class="form-label">Nombre <span class="text-danger">*</span></label>
              <input type="text" id="user_login" name="user_login" class="form-control"
                value="<?= clear_html($user->user_login) ?>" required>
            </div>

            <div class="col-12 col-md-6">
              <label for="user_email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" id="user_email" name="user_email" class="form-control"
                value="<?= clear_html($user->user_email) ?>" required>
            </div>

            <div class="col-12">
              <label for="user_password" class="form-label">Contraseña</label>
              <div class="input-group">
                <input class="form-control" type="password" id="user_password" name="user_password"
                  placeholder="••••••••">
                <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password=""
                  title="Mostrar/Ocultar">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
              <div class="form-text">Actualiza este campo solo si deseas cambiar la contraseña.</div>
            </div>

            <div class="col-12">
              <label for="role_id" class="form-label">Rol Asignado <span class="text-danger">*</span></label>
              <?php if ($roles): ?>
                <select id="role_id" class="form-select" name="role_id" required>
                  <option value="">- Seleccionar -</option>
                  <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->role_id ?>" <?= ((isset($_POST['role_id']) && $_POST['role_id'] == $role->role_id) ||
                        (!isset($_POST['role_id']) && isset($user) && $user->role_id == $role->role_id))
                        ? 'selected' : '' ?>>
                      <?= clear_html($role->role_name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              <?php else: ?>
                <div class="alert alert-warning" role="alert">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i> No hay roles disponibles.
                </div>
              <?php endif; ?>
            </div>

            <div class="col-12">
              <label for="user_status" class="form-label">Estado <span class="text-danger">*</span></label>
              <select id="user_status" class="form-select" name="user_status" required>
                <option value="1" <?= $user->user_status == 1 ? 'selected' : '' ?>>
                  Activo
                </option>
                <option value="0" <?= $user->user_status == 0 ? 'selected' : '' ?>>
                  Inactivo
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- BOTONERA DENTRO DEL COL-8 -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
        <?= Button::cancel(admin_route("users", [], ["p" => ($_GET["p"] ?? 1)]))
          ->icon("fa-solid fa-arrow-left me-2")
          ->render() ?>
        <?= Button::save()->text("Actualizar Usuario")->icon("fa-solid fa-rotate me-2")->render() ?> 
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card mb-3">
        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
          <label class="form-label">Avatar del Usuario</label>
          <input type="file" id="user_image" name="user_image" data-dropimg data-width="150" data-height="150"
            data-aspect="circle" data-default="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>"
            accept="image/*">
          <p class="text-muted small mt-3 mb-0">
            Pulsa para cambiar o arrastra una nueva imagen.
          </p>
        </div>
        <div class="card-footer">
          <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-content-between align-items-center small">
              <span class="text-muted"><i class="fa-solid fa-calendar-check me-1"></i> Registro:</span>
              <span class="fw-medium text-body"><?= format_datetime($user->user_created) ?></span>
            </div>
            <?php if (!empty($user->user_last_login)): ?>
              <div class="d-flex justify-content-between align-items-center small">
                <span class="text-muted"><i class="fa-solid fa-clock-rotate-left me-1"></i> Último acceso:</span>
                <span class="fw-medium text-body"><?= format_datetime($user->user_last_login) ?></span>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>