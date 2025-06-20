<?php $theme->blockStart("style"); ?>
<style>
  .preview-image {
    display: block;
    object-fit: cover;
    border: dotted 1px #e6e6e6;
    margin: 10px auto;
    border-radius: .5rem;
  }

  .preview-logo {
    width: 100px;
    height: 100px;
  }
</style>
<?php $theme->blockEnd("style"); ?>

<?php $theme->blockStart("script"); ?>
<script>
  document.querySelectorAll('.image-input').forEach(function (input) {
    input.addEventListener('change', function (event) {
      const file = event.target.files[0];
      const previewId = input.getAttribute('data-preview');
      const preview = document.getElementById(previewId);

      if (file) {
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function (e) {
            preview.src = e.target.result;
          };
          reader.readAsDataURL(file);
        } else {
          alert('Por favor, selecciona un archivo de imagen válido.');
          preview.src = 'default.webp';
        }
      } else {
        preview.src = 'default.webp';
      }
    });
  });
</script>
<?php $theme->blockEnd("script"); ?>

<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

<form enctype="multipart/form-data" method="post">
  <div class="row">
    <div class="col-8">
      <div class="card mb-3">
        <div class="card-body">

          <input type="hidden" name="user_id" value="<?= $encryption->encrypt($user->user_id) ?>">

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
              value="<?= $encryption->decrypt($user->user_password) ?>">
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
          <label for="post_image" class="form-label">Imagen</label>
          <img id="preview_user_image" class="preview-image preview-logo mb-3"
            src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>">
          <input type="file" class="form-control image-input" name="user_image" data-preview="preview_user_image">
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body text-end">
          <button class="btn btn-primary" type="submit" name="save">Actualizar</button>
          <a href="list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </div>
    </div>
  </div>
</form>

<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>