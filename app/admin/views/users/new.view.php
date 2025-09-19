<?php $theme->blockStart("style"); ?>
<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropimg.css" ?>">
<?php $theme->blockEnd("style"); ?>

<form id="formNewUser" enctype="multipart/form-data" action="" method="post">
  <div class="row g-3">
    <div class="col-8">
      <div class="card">
        <div class="card-body">

          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="user_name" class="form-control"
              value="<?= isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : '' ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="user_email" class="form-control"
              value="<?= isset($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : '' ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control" type="text" name="user_password"
              value="<?= isset($_POST['user_password']) ? htmlspecialchars($_POST['user_password']) : '' ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="user_role" required>
              <option value="">- Seleccionar -</option>
              <option value="2" <?= isset($_POST['user_role']) && $_POST['user_role'] == 2 ? 'selected' : '' ?>>
                Administrador
              </option>
              <option value="3" <?= isset($_POST['user_role']) && $_POST['user_role'] == 3 ? 'selected' : '' ?>>
                Usuario
              </option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="user_status" required>
              <option value="0">- Seleccionar -</option>
              <option value="1" <?= isset($_POST['user_status']) && $_POST['user_status'] == 1 ? 'selected' : '' ?>>
                Activo
              </option>
              <option value="2" <?= isset($_POST['user_status']) && $_POST['user_status'] == 2 ? 'selected' : '' ?>>
                Inactivo
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="col-4">
      <div class="card">
        <div class="card-body">
          <label for="user_image" class="form-label">Imagen</label>
          <!-- <?= SITE_URL ?>/uploads/user/default.webp -->
          <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
            accept=".jpg,.jpeg,.png,.gif,.webp">
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body text-end">
          <button class="btn btn-primary" type="submit" name="save">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</form>

<?php $theme->blockStart("script"); ?>
<script src="<?= SITE_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();
</script>
<?php $theme->blockEnd("script"); ?>