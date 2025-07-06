<?php $theme->blockStart("style"); ?>
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
<?php $theme->blockEnd("script"); ?>

<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

<div class="row g-3">

  <!-- LOGO CLARO -->
  <div class="col-6">
    <div class="card bg-dark text-white">
      <div class="card-body">
        <h5>WHITE LOGO</h5>
        <p>Recommended Size: <b>320 x 71 Pixels</b></p>
        <img id="preview_whitelogo" class="preview-image preview-logo mb-3"
          src="<?= SITE_URL . "/uploads/site/" . ($optionsRaw['white_logo'] ?? 'default-white.webp') ?>" alt="whitelogo"
          height="71">
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input class="form-control image-input" type="file" name="st_whitelogo" data-preview="preview_whitelogo"
              required>
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
        <p>Recommended Size: <b>320 x 71 Pixels</b></p>
        <img id="preview_darklogo" class="preview-image preview-logo mb-3"
          src="<?= SITE_URL . "/uploads/site/" . ($optionsRaw['dark_logo'] ?? 'default-dark.webp') ?>" alt="darklogo"
          height="71">
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input class="form-control image-input" type="file" name="st_darklogo" data-preview="preview_darklogo"
              required>
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
        <p>Recomendado: <b>128 x 128 píxeles</b></p>
        <img id="preview_favicon" class="preview-image preview-favicon mb-3"
          src="<?= SITE_URL . "/uploads/site/favicons/" . ($st_favicon['favicon.ico'] ?? 'favicon.ico') ?>"
          alt="favicon">
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="st_favicon_save" value="<?= $st_favicon['favicon.ico'] ?? '' ?>">
          <div class="mb-3">
            <input class="form-control image-input" type="file" name="st_favicon" data-preview="preview_favicon"
              required>
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
        <p>Recomendado: <b>1200 x 630 píxeles</b></p>
        <img id="preview_og_image" class="preview-image preview-og mb-3"
          src="<?= SITE_URL ?>/uploads/site/<?= $optionsRaw['og_image'] ?? 'default-og.webp' ?>"
          alt="Imagen Open Graph">
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <input id="og_image" class="form-control image-input" type="file" name="st_og_image"
              data-preview="preview_og_image" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Guardar</button>
        </form>
      </div>
    </div>
  </div>

</div>


<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>