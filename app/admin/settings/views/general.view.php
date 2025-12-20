<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/tagify/tagify.css" ?>">

<form action="" method="POST" enctype="multipart/form-data">
  <div class="card">
    <div class="card-body">
      <div class="row g-4">

        <div class="col-lg-7 border-end-lg">
          <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-sliders me-2"></i>Información General</h6>

          <div class="mb-3">
            <label class="form-label fw-semibold">Nombre del Sitio</label>
            <input class="form-control" type="text" value="<?= $optionsRaw['site_name'] ?? '' ?>" name="st_sitename"
              placeholder="Ej: Mi Empresa S.A.">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Descripción</label>
            <textarea class="form-control" name="st_description" rows="3"
              style="field-sizing: content; min-height: 80px;"
              placeholder="Breve descripción para SEO..."><?= $optionsRaw['site_description'] ?? '' ?></textarea>
            <div class="form-text">Aparecerá en los resultados de búsqueda.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Palabras Clave (Keywords)</label>
            <input class="form-control" id="tag-input" type="text" value='<?= $optionsRaw['site_keywords'] ?? "" ?>'
              name="st_keywords" placeholder="Escribe y presiona Enter">
          </div>
        </div>

        <div class="col-lg-5">
          <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-share-nodes me-2"></i>Redes Sociales</h6>

          <div class="mb-3">
            <label class="form-label small text-muted">Facebook</label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="fa-brands fa-facebook text-primary"></i></span>
              <input class="form-control" type="text" value="<?= $optionsRaw["facebook"] ?? "" ?>" name="st_facebook"
                placeholder="URL o Usuario">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Twitter / X</label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="fa-brands fa-x-twitter"></i></span>
              <input class="form-control" type="text" value="<?= $optionsRaw["twitter"] ?? "" ?>" name="st_twitter"
                placeholder="@usuario">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Instagram</label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="fa-brands fa-instagram text-danger"></i></span>
              <input class="form-control" type="text" value="<?= $optionsRaw["instagram"] ?? "" ?>" name="st_instagram"
                placeholder="@usuario">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">Youtube</label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="fa-brands fa-youtube text-danger"></i></span>
              <input class="form-control" type="text" value="<?= $optionsRaw["youtube"] ?? "" ?>" name="st_youtube"
                placeholder="Canal URL">
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end py-3">
      <button class="btn btn-primary px-4" type="submit">
        <i class="fa-solid fa-save me-1"></i> Guardar Cambios
      </button>
    </div>

  </div>
</form>

<script src="<?= SITE_URL . "/static/plugins/tagify/tagify.js" ?>"></script>
<script>
  // Inicialización de Tagify con estilos ajustados a Bootstrap
  const input = document.getElementById('tag-input');
  if (input) {
    new Tagify(input, {
      maxTags: 10,
      dropdown: {
        maxItems: 20,
        classname: "tags-look",
        enabled: 0,
        closeOnSelect: false
      }
    });
  }
</script>