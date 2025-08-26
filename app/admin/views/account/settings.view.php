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

<div class="tab tab-vertical">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-bs-toggle="pill" data-bs-target=".tab-account" role="tab"
        aria-selected="true">
        <i class="fa fa-user-circle me-2"></i>
        Account
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="pill" data-bs-target=".tab-security" role="tab"
        aria-selected="false">
        <i class="fa fa-shield me-2"></i>
        Security
      </a>
    </li>
  </ul>
  <!-- Tab Content-->
  <div class="tab-content">
    <!-- Account-->
    <div class="tab-pane fade show active tab-account" role="tabpanel">
      <h4 class="tab-title">
        <i class="fa fa-user me-2"></i>
        Account Settings
      </h4>
      <form enctype="multipart/form-data" method="POST">
        <div class="mb-3">
          <figure class="text-center">
            <img id="preview_user_image" class="img-fluid rounded-circle preview-image preview-logo mb-3"
              src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" width="100" height="100">
          </figure>
          <input type="file" class="form-control image-input" name="user_image" data-preview="preview_user_image"
            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        </div>

        <div class="mb-3">
          <label for="name" class="form-label">Nombre de usuario</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Tu nombre de usuario"
            value="<?= $user->user_name ?>" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="text" name="email" id="email" class="form-control" placeholder="Tu Email"
            value="<?= $user->user_email ?>" required>
        </div>
        <input type="hidden" name="id" value="<?= $user->user_id ?>">

        <button name="update_profile" type="submit" class="btn btn-primary">Guardar Cambios</button>

      </form>
    </div>
    <!-- Security-->
    <div class="tab-pane fade tab-security" role="tabpanel">
      <h6 class="tab-title mb-3">
        <i class="fa fa-lock me-2"></i>
        Security
      </h6>
      <form action="" method="POST">
        <div class="mb-3">
          <label for="current_password" class="form-label">Contraseña actual</label>
          <input type="password" name="current_password" id="current_password" class="form-control"
            placeholder="Ingresa tu contraseña actual" value="">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Nueva contraseña</label>
          <input type="password" name="password" id="password" class="form-control"
            placeholder="Ingresa la nueva contraseña" value="">
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirmar contraseña</label>
          <input type="password" name="confirm_password" id="confirm_password" class="form-control"
            placeholder="Confirma la nueva contraseña" value="">
        </div>

        <div class="mb-3 d-flex justify-content-end">
          <button name="change_password" type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


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