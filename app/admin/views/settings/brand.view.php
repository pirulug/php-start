<!-- <?php $theme->blockStart("style"); ?>
<style>
  .preview-image {
    display: block;
    object-fit: contain;
    border: dotted 1px #e6e6e6;
    margin: 10px auto;
    border-radius: .5rem;
  }

  .preview-logo {
    width: 320px;
    height: 71px;
  }

  .preview-favicon {
    width: 150px;
    height: 150px;
  }

  .preview-og {
    width: 300px;
    height: 150px;
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
<?php $theme->blockEnd("script"); ?> -->

<?php $theme->blockStart("style"); ?>
<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropimg.css" ?>">
<?php $theme->blockEnd("style"); ?>

<div class="row g-3">

  <!-- LOGO CLARO -->
  <div class="col-6">
    <div class="card bg-dark text-white">
      <div class="card-body">
        <h5>WHITE LOGO</h5>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input type="file" id="st_whitelogo" name="st_whitelogo" data-dropimg data-width="300" data-height="71"
              data-default="<?= SITE_URL . "/uploads/site/" . $optionsRaw['white_logo'] ?>"
              accept=".jpg,.jpeg,.png,.gif,.webp" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Guardar</button>
        </form>
      </div>
    </div>
  </div>

  <!-- LOGO OSCURO -->
  <div class="col-6">
    <div class="card bg-white text-dark">
      <div class="card-body">
        <h5>DARK LOGO</h5>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input type="file" id="st_darklogo" name="st_darklogo" data-dropimg data-width="300" data-height="71"
              data-default="<?= SITE_URL . "/uploads/site/" . $optionsRaw['dark_logo'] ?>"
              accept=".jpg,.jpeg,.png,.gif,.webp" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Guardar</button>
        </form>
      </div>
    </div>
  </div>

  <!-- FAVICON -->
  <div class="col-6">
    <div class="card">
      <div class="card-body">
        <h5>FAVICON</h5>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input type="file" id="st_favicon" name="st_favicon" data-dropimg data-width="128" data-height="128"
              data-default="<?= SITE_URL . "/uploads/site/favicons/" . $st_favicon['favicon.ico'] ?>"
              accept=".jpg,.jpeg,.png,.gif,.webp" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Guardar</button>
        </form>
      </div>
    </div>
  </div>

  <!-- OPEN GRAPH IMAGE -->
  <div class="col-6">
    <div class="card">
      <div class="card-body">
        <h5>IMAGEN OPEN GRAPH</h5>
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input type="file" id="st_og_image" name="st_og_image" data-dropimg data-width="1200" data-height="630"
              data-default="<?= SITE_URL ?>/uploads/site/<?= $optionsRaw['og_image'] ?>"
              accept=".jpg,.jpeg,.png,.gif,.webp" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Guardar</button>
        </form>
      </div>
    </div>
  </div>

</div>

<?php $theme->blockStart("script"); ?>
<script src="<?= SITE_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();
</script>
<?php $theme->blockEnd("script"); ?>