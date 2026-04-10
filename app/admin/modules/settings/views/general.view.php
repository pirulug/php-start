<?php start_block('title'); ?>
Ajustes Generales
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes']
]) ?>
<?php end_block(); ?>

<?php start_block("css"); ?>
<link rel="stylesheet" href="<?= APP_URL . "/static/plugins/tagify/tagify.css" ?>">
<?php end_block(); ?>

<form action="" method="POST" enctype="multipart/form-data">
  <div class="card">
    <div class="card-body">
      <div class="row g-4">

        <div class="col-lg-12">
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
      </div>
    </div>

    <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end py-3">
      <button class="btn btn-primary px-4" type="submit">
        <i class="fa-solid fa-save me-1"></i> Guardar Cambios
      </button>
    </div>

  </div>
</form>

<?php start_block("js"); ?>
<script src="<?= APP_URL . "/static/plugins/tagify/tagify.js" ?>"></script>
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
<?php end_block(); ?>