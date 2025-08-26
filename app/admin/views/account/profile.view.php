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

<div class="card mb-3">
  <div class="card-body p-4">
    <div class="position-relative mb-5">
      <img class="img-fluid w-100 rounded shadow" src="https://dummyimage.com/1200x300/ddd/000.jpg" alt="Cover Image">
      <div class="position-absolute top-100 start-50 translate-middle">
        <img class="img-fluid rounded-circle border border-3 border-white shadow"
          src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" width="170" height="170" alt="Pirulug Avatar">
      </div>
    </div>
    <div class="pt-5">
      <h3 class="mb-1 text-center"><?= ($user->user_name) ?></h3>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Activities</h5>
  </div>
  <div class="card-body h-100">
    <div class="d-flex align-items-start mb-3">
      <img class="rounded-circle me-2" src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" width="36"
        height="36" alt="Pirulug">
      <div class="flex-grow-1">
        <small class="float-end text-primary">now</small>
        <strong>Pirulug</strong>
        pushed new commits to<strong>GitHub</strong>
        <br>
        <small class="text-muted">Today</small>
      </div>
    </div>
    <hr>
    <div class="d-grid"><a class="btn btn-primary" href="https://github.com/Pirulug" target="_blank">See
        more on GitHub</a></div>
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