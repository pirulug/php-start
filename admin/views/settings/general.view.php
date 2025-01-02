<?php $theme->blockStart("style"); // Block ?>
<link rel="stylesheet" href="<?= SITE_URL ?>/admin/assets/css/tagify.css">
<style>
  #preview-image {
    display: block;
    width: 300px;
    height: 180px;
    object-fit: cover;
    border: solid 1px #e6e6e6;
    /* background: #e6e6e6; */
    margin: 10px auto;
    border-radius: .5rem;
  }
</style>
<?php $theme->blockEnd("style"); ?>

<?php $theme->blockStart("script"); // Block ?>
<script src="<?= SITE_URL ?>/admin/assets/js/tagify.js"></script>
<script>
  const input = document.getElementById('tag-input');
  new Tagify(input);
</script>
<script>
  document.getElementById('og_image').addEventListener('change', function (event) {
    const input = event.target;
    const preview = document.getElementById('preview-image');
    const file = input.files[0];

    if (file) {
      // Verificar que sea un archivo de imagen
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();

        // Leer el archivo como una URL
        reader.onload = function (e) {
          preview.src = e.target.result; // Asignar la URL generada al src de la imagen
        };

        reader.readAsDataURL(file); // Leer el contenido del archivo
      } else {
        alert('Por favor, selecciona un archivo de imagen válido.');
        preview.src = 'default.webp'; // Restaurar la imagen por defecto
      }
    } else {
      // Si no hay archivo, restaurar la imagen por defecto
      preview.src = 'default.webp';
    }
  });
</script>
<?php $theme->blockEnd("script"); ?>

<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>

<div class="card">
  <div class="card-body">
    <form action="" method="POST" enctype="multipart/form-data">
      <h3 class="h5 m-0">General</h3>
      <hr>
      <div class="mb-3">
        <label class="form-label">Site Name</label>
        <input class="form-control" type="text" value="<?= $settings->st_sitename ?>" name="st_sitename">
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="st_description"
          style="field-sizing: content;min-height: 3lh;"><?= $settings->st_description ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Keywords</label>
        <input class="form-control" id="tag-input" type="text" value='<?= $settings->st_keywords ?>' name="st_keywords">
      </div>
      <div class="mb-3">
        <label class="form-label">Imagen Open Graph</label>
        <img id="preview-image" src="<?= SITE_URL ?>/uploads/site/<?= $settings->st_og_image ?>"
          alt="Imagen Open Graph">
        <input class="form-control" type="file" name="og_image" id="og_image">
      </div>
      <h3 class="h5 m-0">Social</h3>
      <hr>
      <div class="mb-3">
        <label class="form-label">Facebook</label>
        <input class="form-control" type="text" value="<?= $settings->st_facebook ?>" name="st_facebook">
      </div>
      <div class="mb-3">
        <label class="form-label">Twitter</label>
        <input class="form-control" type="text" value="<?= $settings->st_twitter ?>" name="st_twitter">
      </div>
      <div class="mb-3">
        <label class="form-label">Instagram</label>
        <input class="form-control" type="text" value="<?= $settings->st_instagram ?>" name="st_instagram">
      </div>
      <div class="mb-3">
        <label class="form-label">Youtube</label>
        <input class="form-control" type="text" value="<?= $settings->st_youtube ?>" name="st_youtube">
      </div>

      <hr>
      <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/bottom.partial.php"; ?>