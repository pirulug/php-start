<?php $theme->blockStart("style"); ?>
<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropzone.css" ?>">

<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropimg.css" ?>">
<?php $theme->blockEnd("style"); ?>

<!-- Ejemplo múltiple -->
<div class="card mb-3">
  <div class="card-body">
    <label>Subir Documentos:</label>
    <input type="file" name="docs" multiple data-pdz data-pdz-multiple="true" data-pdz-max="5" data-pdz-thumb-width="50"
      data-pdz-thumb-height="50" accept=".pdf,application/pdf">
  </div>
</div>

<!-- Ejemplo único -->
<div class="card">
  <div class="card-body">
    <label>Subir Imagen:</label>
    <input type="file" name="foto" data-pdz data-pdz-width="1280" data-pdz-height="720"
      data-pdz-accept=".jpg,.jpeg,.png,.gif,.webp" accept=".jpg,.jpeg,.png,.gif,.webp">
  </div>
</div>

<h3>imgDROP</h3>
<div class="card card-body mb-3">
  <input type="file" name="foto1" data-dropimg data-width="350" data-height="500">
</div>

<div class="card card-body mb-3">
  <input type="file" name="foto2" data-dropimg data-width="250" data-height="250">
</div>

<div class="card card-body mb-3">
  <input type="file" name="foto3" data-dropimg data-width="400" data-height="300">
</div>

<div class="card card-body mb-3">
  <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
    data-default="<?= SITE_URL . '/uploads/user/default.webp' ?>" accept=".jpg,.jpeg,.png,.gif,.webp">

</div>


<?php $theme->blockStart("script"); ?>
<script src="<?= SITE_URL . "/static/plugins/dropzone/dropzone.js" ?>"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("input[data-pdz]").forEach((input) => {
      new PirulugDropzone(input);
    });
  });
</script>

<script src="<?= SITE_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();
</script>
<?php $theme->blockEnd("script"); ?>