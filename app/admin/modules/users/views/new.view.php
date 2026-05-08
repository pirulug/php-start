<?php start_block('title'); ?>
Nuevo Usuario
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Usuarios', 'link' => admin_route('users')],
  ['label' => 'Nuevo']
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


<form id="formNewUser" enctype="multipart/form-data" action="" method="post" autocomplete="off">

  <div class="row g-3">

    <div class="col-12 col-lg-8">
      <div class="card mb-3">

        <div class="card-body">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label for="user_login" class="form-label">Usuario <span class="text-danger">*</span></label>
              <input type="text" id="user_login" name="user_login" class="form-control" placeholder="Ej: jdoe"
                value="<?= isset($_POST['user_login']) ? clear_html($_POST['user_login']) : '' ?>" required>
            </div>

            <div class="col-12 col-md-6">
              <label for="user_email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" id="user_email" name="user_email" class="form-control" placeholder="nombre@correo.com"
                value="<?= isset($_POST['user_email']) ? clear_html($_POST['user_email']) : '' ?>" required>
            </div>

            <div class="col-12">
              <label for="user_password" class="form-label">Contraseña</label>
              <div class="input-group">
                <input class="form-control" type="password" id="user_password" name="user_password"
                  placeholder="••••••••"
                  value="<?= isset($_POST['user_password']) ? clear_html($_POST['user_password']) : '' ?>">
                <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password=""
                  title="Mostrar/Ocultar">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
              <div class="form-text">Dejar en blanco para generar una automáticamente.</div>
            </div>

            <div class="col-12">
              <label for="role_id" class="form-label">Rol de Usuario <span class="text-danger">*</span></label>
              <?php if ($roles): ?>
                <select id="role_id" class="form-select" name="role_id" required>
                  <option value="">Seleccionar un rol...</option>
                  <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->role_id ?>" <?= isset($_POST['role_id']) && $_POST['role_id'] == $role->role_id ? 'selected' : '' ?>>
                      <?= clear_html($role->role_name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              <?php else: ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i>
                  <div>No hay roles creados. <a href="<?= admin_route('roles') ?>" class="alert-link">Crear uno ahora</a>.
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="col-12">
              <label for="user_status" class="form-label">Estado de la cuenta <span class="text-danger">*</span></label>
              <select id="user_status" class="form-select" name="user_status" required>
                <option value="1" <?= isset($_POST['user_status']) && $_POST['user_status'] == 1 ? 'selected' : '' ?>>
                  Activo
                </option>
                <option value="0" <?= (isset($_POST['user_status']) && $_POST['user_status'] == 0) || !isset($_POST['user_status']) ? 'selected' : '' ?>>
                  Inactivo / Pendiente
                </option>
              </select>
            </div>

          </div>
        </div>
      </div>

      <!-- BOTONERA DENTRO DEL COL-8 -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
        <?= Button::cancel(admin_route("users"))->render() ?>
        <?= Button::save()->render() ?> 
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card mb-3">
        <div class="card-body text-center">
          <label class="form-label">Imagen de Perfil</label>
          <input type="file" id="user_image" name="user_image" data-dropimg data-width="150" data-height="150"
            data-aspect="circle" accept="image/*">
          <p class="text-muted small mt-3 mb-0">
            Sube una foto cuadrada para mejores resultados.
          </p>
        </div>
      </div>
    </div>

  </div>
</form>