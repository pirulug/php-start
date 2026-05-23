<?php block_start("title"); ?>
Ajustes Generales
<?php block_end(); ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Settings"],
  ["label" => "General"]
]) ?>
<?php block_end(); ?>

<?php block_start("css"); ?>
<?= static_libs_css("tagify", "tagify.css") ?>
<?php block_end(); ?>

<?php block_start("js"); ?>
<?= static_libs_js("tagify", "tagify.js") ?>
<?= url_script_admin("settings", "general") ?>
<?php block_end(); ?>

<form action="" method="POST" enctype="multipart/form-data">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-sliders"></i>
            Información General
          </h6>

          <div class="mb-3">
            <label for="st_sitename" class="form-label">Nombre del Sitio <span class="text-danger">*</span></label>
            <input type="text" id="st_sitename" name="st_sitename" class="form-control"
              value="<?= htmlspecialchars($config->siteName() ?? '') ?>" placeholder="Ej: Mi Empresa S.A." required>
          </div>

          <div class="mb-3">
            <label for="st_description" class="form-label">Descripción SEO (Meta Description)</label>
            <textarea name="st_description" id="st_description" class="form-control" rows="3"
              placeholder="Breve descripción para buscadores..."><?= htmlspecialchars($config->siteDescription() ?? '') ?></textarea>
            <div class="form-text small">Este texto es el que aparece en los resultados de búsqueda de Google.</div>
          </div>

          <div class="mb-0">
            <label for="tag-input" class="form-label">Palabras Clave (Keywords)</label>
            <input type="text" id="tag-input" name="st_keywords" class="form-control"
              value="<?= htmlspecialchars($config->siteKeywords() ?? '') ?>" placeholder="Escribe y presiona Enter">
            <div class="form-text small">Presiona Enter para agregar cada etiqueta.</div>
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-spinner"></i>
            Pantalla de Carga (Preloader)
          </h6>

          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="st_loader_front" name="st_loader_front" value="true"
              <?= $config->get("loader_front") === "true" ? "checked" : "" ?>>
            <label class="form-check-label" for="st_loader_front">Activar pantalla de carga en el Sitio Público (Front)</label>
          </div>

          <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" id="st_loader_admin" name="st_loader_admin" value="true"
              <?= $config->get("loader_admin") === "true" ? "checked" : "" ?>>
            <label class="form-check-label" for="st_loader_admin">Activar pantalla de carga en el Panel de Administración (Admin)</label>
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-bullhorn"></i>
            Barra de Anuncios (Announcement Bar)
          </h6>

          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="st_announcement_active" name="st_announcement_active" value="true"
              <?= $config->get("announcement_active") === "true" ? "checked" : "" ?>>
            <label class="form-check-label" for="st_announcement_active">Activar Barra de Anuncios en el Sitio Público (Front)</label>
          </div>

          <div class="mb-3">
            <label for="st_announcement_type" class="form-label">Estilo / Tipo de Anuncio</label>
            <select class="form-select" id="st_announcement_type" name="st_announcement_type">
              <option value="primary" <?= $config->get("announcement_type", "primary") === "primary" ? "selected" : "" ?>>Primario</option>
              <option value="secondary" <?= $config->get("announcement_type") === "secondary" ? "selected" : "" ?>>Secundario</option>
              <option value="success" <?= $config->get("announcement_type") === "success" ? "selected" : "" ?>>Éxito (Verde)</option>
              <option value="danger" <?= $config->get("announcement_type") === "danger" ? "selected" : "" ?>>Peligro (Rojo)</option>
              <option value="warning" <?= $config->get("announcement_type") === "warning" ? "selected" : "" ?>>Advertencia (Amarillo)</option>
              <option value="info" <?= $config->get("announcement_type") === "info" ? "selected" : "" ?>>Información (Azul)</option>
              <option value="dark" <?= $config->get("announcement_type") === "dark" ? "selected" : "" ?>>Oscuro (Negro)</option>
              <option value="light" <?= $config->get("announcement_type") === "light" ? "selected" : "" ?>>Claro (Gris claro)</option>
            </select>
          </div>

          <div class="mb-0">
            <label for="st_announcement_text" class="form-label">Contenido del Anuncio</label>
            <textarea name="st_announcement_text" id="st_announcement_text" class="form-control" rows="3"
              placeholder="Ej: ¡Llegó un nuevo curso! Conoce todas las novedades aquí <a href='#'>ver más</a>..."><?= htmlspecialchars($config->get("announcement_text") ?? '') ?></textarea>
            <div class="form-text small">Puedes utilizar HTML ligero para enlaces (a) o negrita (strong).</div>
          </div>
        </div>
      </div>

      <!-- Botonera Pegajosa -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom mt-3">
        <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar Cambios
        </button>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-info"></i>
            Ayuda
          </h6>
          <p class="text-body small mb-0">
            La información configurada aquí es vital para la identidad de su sitio y su posicionamiento en buscadores.
          </p>
          <hr class="my-3 opacity-10">
          <ul class="small ps-3 text-secondary mb-0">
            <li><strong>Nombre:</strong> Aparece en la pestaña del navegador.</li>
            <li><strong>Descripción:</strong> Debe tener entre 150 y 160 caracteres.</li>
            <li><strong>Keywords:</strong> Ayudan a categorizar su sitio.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</form>