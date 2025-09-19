<?php $theme->blockStart("style"); ?>
<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropzone.css" ?>">
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