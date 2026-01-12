<?php start_block("title") ?>
Sitemap.xml
<?php end_block() ?>

<?php start_block("css") ?>
<style>
  .border-dashed {
    border-style: dashed !important;
    border-width: 2px !important;
  }

  .border-dashed:hover {
    background-color: var(--bs-primary-bg-subtle);
  }
</style>
<?php end_block() ?>

<div class="card mb-4">
  <div class="card-header   d-flex justify-content-between align-items-center py-3">
    <h5 class="card-title mb-0 d-flex align-items-center gap-2">
      <i class="fa-solid fa-sitemap text-primary"></i>
      Editor de Sitemap
    </h5>
    <button type="submit" form="sitemapForm" class="btn btn-success btn-sm">
      <i class="fa-solid fa-save me-1"></i> Guardar Cambios
    </button>
  </div>

  <div class="card-body p-0">
    <form id="sitemapForm" method="POST">

      <div class="d-none d-lg-flex   px-3 py-2 fw-bold text-body-secondary small text-uppercase">
        <div class="col-5 ps-2">URL del Sitio</div>
        <div class="col-2">Modificación</div>
        <div class="col-2">Frecuencia</div>
        <div class="col-2">Prioridad</div>
        <div class="col-1 text-end">Acción</div>
      </div>

      <div id="url-container" class="list-group list-group-flush">
      </div>

      <div class="p-3 ">
        <button type="button" class="btn btn-outline-primary w-100 border-dashed" onclick="addNewPage()">
          <i class="fa-solid fa-plus me-1"></i> Agregar Nueva Página
        </button>
      </div>

    </form>
  </div>
</div>

<?php start_block("js") ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Obtenemos los datos de PHP
    const pages = <?php echo json_encode($pages); ?>;
    const container = document.getElementById("url-container");

    // Si hay páginas, las cargamos. Si no, agregamos una vacía por defecto.
    if (pages.length > 0) {
      pages.forEach((page) => addPageField(container, page));
    } else {
      addPageField(container);
    }
  });

  // Función para agregar fila
  function addPageField(container, page = {}) {
    // Valores por defecto
    const url = page.loc || '';
    const lastmod = page.lastmod || '';
    const priority = page.priority || '0.5';
    const freq = page.changefreq || 'monthly';

    const div = document.createElement("div");
    div.classList.add("list-group-item", "page-row", "p-3");

    // HTML de la fila
    div.innerHTML = `
      <div class="row g-2 align-items-center">
        
        <div class="col-12 col-lg-5">
          <label class="d-lg-none small text-muted mb-1">URL</label>
          <div class="input-group">
            <span class="input-group-text "><i class="fa-solid fa-globe text-muted"></i></span>
            <input type="text" class="form-control" name="url[]" value="${url}" placeholder="https://..." required>
          </div>
        </div>

        <div class="col-6 col-lg-2">
           <label class="d-lg-none small text-muted mb-1">Modificación</label>
           <input type="date" class="form-control" name="lastmod[]" value="${lastmod}" required>
        </div>

        <div class="col-6 col-lg-2">
           <label class="d-lg-none small text-muted mb-1">Frecuencia</label>
           <select name="changefreq[]" class="form-select">
             <option value="daily" ${freq === "daily" ? "selected" : ""}>Diario</option>
             <option value="weekly" ${freq === "weekly" ? "selected" : ""}>Semanal</option>
             <option value="monthly" ${freq === "monthly" ? "selected" : ""}>Mensual</option>
             <option value="yearly" ${freq === "yearly" ? "selected" : ""}>Anual</option>
             <option value="never" ${freq === "never" ? "selected" : ""}>Nunca</option>
           </select>
        </div>

        <div class="col-10 col-lg-2">
           <label class="d-lg-none small text-muted mb-1">Prioridad (0.0 - 1.0)</label>
           <div class="input-group">
             <span class="input-group-text "><i class="fa-solid fa-sort text-muted"></i></span>
             <input type="number" class="form-control" name="priority[]" value="${priority}" min="0.0" max="1.0" step="0.1" required>
           </div>
        </div>

        <div class="col-2 col-lg-1 text-end">
           <label class="d-lg-none small text-muted mb-1">&nbsp;</label>
           <button type="button" class="btn btn-outline-danger w-100 border-0" onclick="removePageField(this)" title="Eliminar fila">
             <i class="fa-solid fa-trash-can"></i>
           </button>
        </div>

      </div>
    `;

    container.appendChild(div);
  }

  // Función para eliminar (más robusta: busca el padre más cercano)
  function removePageField(button) {
    const row = button.closest(".page-row");
    const container = document.getElementById("url-container");

    // Evitar dejar el formulario vacío (opcional, buena práctica UX)
    if (container.children.length > 1) {
      if (confirm('¿Eliminar esta entrada?')) {
        row.remove();
      }
    } else {
      // Si es el último, solo limpiamos los valores en lugar de borrar la fila
      alert("Debe haber al menos una página.");
    }
  }

  // Wrapper para el botón de agregar
  function addNewPage() {
    const container = document.getElementById("url-container");
    addPageField(container);
  }
</script>
<?php end_block() ?>