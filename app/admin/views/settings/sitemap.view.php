
<script>
  // Cargar las páginas existentes al cargar el formulario
  document.addEventListener("DOMContentLoaded", function () {
    const pages = <?php echo json_encode($pages); ?>;
    const container = document.getElementById("url-container");
    pages.forEach((page, index) => {
      addPageField(container, page, index);
    });
  });

  // Función para agregar un conjunto de campos para una página
  function addPageField(container, page = {}, index = null) {
    const div = document.createElement("div");
    div.classList.add("page-fields", "mb-3");
    div.setAttribute("data-index", index);

    div.innerHTML = `
                <div class="row g-3">
                    <div class="col-12 col-md-5">
                        <label class="form-label">URL:</label>
                        <input type="text" class="form-control" name="url[]" value="${page.loc || ''}" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Última modificación:</label>
                        <input type="date" class="form-control" name="lastmod[]" value="${page.lastmod || ''}" required>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label">Frecuencia de cambio:</label>
                        <select name="changefreq[]" class="form-select" required>
                            <option value="daily" ${page.changefreq === "daily" ? "selected" : ""}>Diario</option>
                            <option value="weekly" ${page.changefreq === "weekly" ? "selected" : ""}>Semanal</option>
                            <option value="monthly" ${page.changefreq === "monthly" ? "selected" : ""}>Mensual</option>
                            <option value="yearly" ${page.changefreq === "yearly" ? "selected" : ""}>Anual</option>
                            <option value="never" ${page.changefreq === "never" ? "selected" : ""}>Nunca</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label">Prioridad:</label>
                        <input type="number" class="form-control" name="priority[]" value="${page.priority || 0.5}" min="0.0" max="1.0" step="0.1" required>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-danger" onclick="removePageField(${index})">Eliminar</button>
                    </div>
                </div>
            `;

    container.appendChild(div);
  }

  // Función para eliminar un conjunto de campos
  function removePageField(index) {
    const container = document.getElementById("url-container");
    const pageField = container.querySelector(`.page-fields[data-index="${index}"]`);
    if (pageField) {
      container.removeChild(pageField);
    }
  }

  // Función para agregar una nueva página
  function addNewPage() {
    const container = document.getElementById("url-container");
    addPageField(container);
  }
</script>

<div class="card">
  <div class="card-body">
    <form method="POST">
      <div id="url-container"></div>
      <button type="button" class="btn btn-primary mb-3" onclick="addNewPage()">Agregar Página</button>
      <button type="submit" class="btn btn-success mb-3">Guardar Cambios</button>
    </form>
  </div>
</div>