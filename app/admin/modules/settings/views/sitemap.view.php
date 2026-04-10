<?php start_block("title") ?>
Gestion de Sitemap
<?php end_block() ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Sitemap XML']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<style>
  .sitemap-row:hover {
    background-color: #f8f9fa;
  }
  [data-bs-theme="dark"] .sitemap-row:hover {
    background-color: #2b3035;
  }
  .priority-badge {
    width: 40px;
    text-align: center;
    font-weight: bold;
    font-size: 0.8rem;
    background-color: #fcd;
    color: #f05;
    border: 1px solid #f9b;
  }
  [data-bs-theme="dark"] .priority-badge {
    background-color: #301;
    color: #f69;
    border-color: #903;
  }
  .btn-add-row {
    border: 2px dashed #dee2e6;
    transition: all 0.3s ease;
    color: #6c757d;
  }
  .btn-add-row:hover {
    border-color: #f05;
    background-color: #fcd;
    color: #f05;
  }
  [data-bs-theme="dark"] .btn-add-row {
    border-color: #495057;
  }
  [data-bs-theme="dark"] .btn-add-row:hover {
    background-color: #330011;
  }
</style>
<?php end_block() ?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center py-3">
        <div>
          <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-sitemap me-2"></i>Editor de Sitemap XML</h6>
          <small class="text-muted">Configura manualmente la indexacion de tus paginas principales.</small>
        </div>
        <div>
           <?= ActionBtn::save()->attrs('form="sitemapForm"')->text('Guardar Cambios')->render() ?>
        </div>
      </div>

      <div class="card-body p-0">
        <form id="sitemapForm" method="POST">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="bg-body-tertiary">
                <tr>
                  <th class="ps-4">URL del Sitio</th>
                  <th style="width: 180px;">Ultima Modificacion</th>
                  <th style="width: 150px;">Frecuencia</th>
                  <th style="width: 120px;">Prioridad</th>
                  <th class="text-end pe-4" style="width: 80px;">Accion</th>
                </tr>
              </thead>
              <tbody id="url-container">
                <!-- Filas dinamicas se cargan aqui -->
              </tbody>
            </table>
          </div>

          <div class="p-4">
            <button type="button" class="btn btn-add-row w-100 py-3 fw-bold text-uppercase small" onclick="addNewPage()">
              <i class="fa-solid fa-plus-circle me-1"></i> Agregar Nueva URL al Sitemap
            </button>
          </div>
        </form>
      </div>

      <div class="card-footer py-3">
          <p class="mb-0 small text-muted">
            <i class="fa-solid fa-circle-info me-1"></i> 
            El archivo se guardara en la raiz de tu sitio como <code>sitemap.xml</code>. Google y otros buscadores lo leeran automaticamente.
          </p>
      </div>
    </div>
  </div>
</div>

<?php start_block("js") ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const pages = <?php echo json_encode($pages); ?>;
    const container = document.getElementById("url-container");

    if (pages.length > 0) {
      pages.forEach((page) => addPageField(container, page));
    } else {
      addPageField(container, { loc: '<?= APP_URL ?>', lastmod: '<?= date('Y-m-d') ?>', priority: '1.0', changefreq: 'daily' });
    }
  });

  function addPageField(container, page = {}) {
    const url = page.loc || '';
    const lastmod = page.lastmod || '<?= date('Y-m-d') ?>';
    const priority = page.priority || '0.5';
    const freq = page.changefreq || 'monthly';

    const tr = document.createElement("tr");
    tr.classList.add("sitemap-row");

    tr.innerHTML = `
      <td class="ps-4">
        <div class="input-group input-group-sm">
          <span class="input-group-text"><i class="fa-solid fa-link small opacity-50"></i></span>
          <input type="text" class="form-control" name="url[]" value="${url}" placeholder="https://..." required>
        </div>
      </td>
      <td>
        <input type="date" class="form-control form-control-sm" name="lastmod[]" value="${lastmod}" required>
      </td>
      <td>
        <select name="changefreq[]" class="form-select form-select-sm">
          <option value="always" ${freq === "always" ? "selected" : ""}>Siempre</option>
          <option value="hourly" ${freq === "hourly" ? "selected" : ""}>Cada hora</option>
          <option value="daily" ${freq === "daily" ? "selected" : ""}>Diario</option>
          <option value="weekly" ${freq === "weekly" ? "selected" : ""}>Semanal</option>
          <option value="monthly" ${freq === "monthly" ? "selected" : ""}>Mensual</option>
          <option value="yearly" ${freq === "yearly" ? "selected" : ""}>Anual</option>
          <option value="never" ${freq === "never" ? "selected" : ""}>Nunca</option>
        </select>
      </td>
      <td>
        <div class="d-flex align-items-center gap-2">
          <input type="range" class="form-range flex-grow-1" name="priority[]" value="${priority}" min="0.0" max="1.0" step="0.1" oninput="this.nextElementSibling.innerText = parseFloat(this.value).toFixed(1)">
          <span class="badge priority-badge">${parseFloat(priority).toFixed(1)}</span>
        </div>
      </td>
      <td class="text-end pe-4">
        <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removePageField(this)" title="Eliminar fila">
          <i class="fa-solid fa-trash-can"></i>
        </button>
      </td>
    `;

    container.appendChild(tr);
  }

  function removePageField(button) {
    const row = button.closest("tr");
    const container = document.getElementById("url-container");

    if (container.rows.length > 1) {
      row.style.opacity = '0';
      row.style.transform = 'translateX(20px)';
      row.style.transition = 'all 0.3s ease';
      setTimeout(() => row.remove(), 300);
    } else {
      alert("El sitemap debe contener al menos una entrada.");
    }
  }

  function addNewPage() {
    const container = document.getElementById("url-container");
    addPageField(container);
    // Scroll suave a la nueva fila
    container.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'end' });
  }
</script>
<?php end_block() ?>