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
          <label for="post_image" class="form-label">Imagen</label>
          <img id="preview_user_image" class="preview-image preview-logo mb-3"
            src="<?= SITE_URL ?>/uploads/user/default.webp">
          <input type="file" class="form-control image-input" name="user_image" data-preview="preview_user_image">
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

<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>