<?php $theme->blockStart("style"); ?>
<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropzone.css" ?>">
<?php $theme->blockEnd("style"); ?>

<form enctype="multipart/form-data" method="post">
  <div class="row">
    <div class="col-8">
      <div class="card mb-3">
        <div class="card-body">

          <input type="hidden" name="user_id" value="<?= $cipher->encrypt($user->user_id) ?>">

          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="user_name" class="form-control" value="<?= $user->user_name ?>" required>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="text" name="user_email" class="form-control" value="<?= $user->user_email ?>" required>
          </div>

          <div class="mb-3">
            <label>Password</label>
            <input class="form-control" type="text" name="user_password"
              value="<?= $cipher->decrypt($user->user_password) ?>">
          </div>

          <div class="mb-3">
            <label class="control-label">Role</label>
            <select class="form-select" name="user_role" required>
              <option value="0">- Seleccionar -</option>
              <option value="2" <?= $user->user_role == 2 ? 'selected' : '' ?>>
                Administrador
              </option>
              <option value="3" <?= $user->user_role == 3 ? 'selected' : '' ?>>
                Suscriptor
              </option>
            </select>
          </div>

          <div class="mb-3">
            <label class="control-label">Status</label>
            <select class="form-select" name="user_status" required>
              <option value="0">- Seleccionar -</option>
              <option value="1" <?= $user->user_status == 1 ? 'selected' : '' ?>>
                Activo
              </option>
              <option value="2" <?= $user->user_status == 2 ? 'selected' : '' ?>>
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
          <!-- <label for="post_image" class="form-label">Imagen</label>
          <img id="preview_user_image" class="preview-image preview-logo mb-3"
            src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>">
          <input type="file" class="form-control image-input" name="user_image" data-preview="preview_user_image"> -->
          <input type="file" id="user_image" name="user_image" data-pdz data-pdz-width="100" data-pdz-height="100"
            data-pdz-default="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" accept=".jpg,.jpeg,.png,.gif,.webp">
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body text-end">
          <button class="btn btn-primary" type="submit" name="save">Actualizar</button>
          <a href="<?= panel_url('users') ?>" class="btn btn-secondary">Cancelar</a>
        </div>
      </div>
    </div>
  </div>
</form>

<?php $theme->blockStart("script"); ?>
<script src="<?= SITE_URL . "/static/plugins/dropzone/dropzone.js" ?>"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("input[data-pdz]").forEach((input) => {
      new PirulugDropzone(input);
    });
  });
</script>
<?php $theme->blockEnd("script"); ?>