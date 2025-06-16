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

<form enctype="multipart/form-data" method="POST">

  <div class="row">
    <div class="col-md-3 col-xl-4">
      <div class="card mb-3">
        <div class="card-body text-center">

          <img id="preview_user_image" class="img-fluid rounded-circle preview-image preview-logo mb-3"
            src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" width="100" height="100">

          <h5 class="card-title mb-0 text-uppercase"><?= $user->user_name ?></h5>
          <h6 class="text-muted mb-3">
            <?php if ($user->user_role == 0): ?>
              <span class="badge bg-danger-subtle">Super Admin</span>
            <?php elseif ($user->user_role == 1): ?>
              <span class="badge bg-info-subtle">Admin</span>
            <?php else: ?>
              <span class="badge bg-success-subtle">Usuario</span>
            <?php endif; ?>
          </h6>

          <input type="file" class="form-control image-input" name="user_image" data-preview="preview_user_image">
        </div>
      </div>
    </div>
    <div class="col-md-9 col-xl-8">
      <div class="card mb-3">
        <div class="card-body h-100">
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
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

</form>

<div class="card">
  <div class="card-header">
    <h5 class="card-title">Actividades</h5>
  </div>
  <div class="card-body">
    <?php foreach ($log->getLogsByUser($user->user_id) as $log): ?>
      <div class="d-flex align-items-start">
        <img class="rounded-circle me-2" src="<?= get_gravatar($log->user_email) ?>" alt="<?= $log->user_name ?>"
          width="36" height="36">
        <div class="flex-grow-1">
          <small class="float-end text-navy"><?= tiempoDesdeCambio($log->timestamp) ?></small>
          <!-- <strong><?= $log->user_name ?></strong> -->
          <?= $log->description ?>
          <br>
          <small class="text-muted"><?= $log->timestamp ?></small>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>