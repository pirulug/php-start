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

<?php start_block("js") ?>
<?= url_script_admin('settings', 'sitemap') ?>
<?php end_block() ?>

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
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-sitemap me-2"></i>Editor de Sitemap XML</h6>
          <small class="text-muted">Configura manualmente la indexacion de tus paginas principales.</small>
        </div>
      </div>

      <div class="card-body">
        <form id="sitemapForm" method="POST">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th class="ps-4">URL del Sitio</th>
                  <th style="width: 180px;">Ultima Modificacion</th>
                  <th style="width: 150px;">Frecuencia</th>
                  <th style="width: 120px;">Prioridad</th>
                  <th class="text-end pe-4" style="width: 80px;">Accion</th>
                </tr>
              </thead>
              <tbody id="url-container" data-pages='<?= json_encode($pages) ?>' data-app-url='<?= APP_URL ?>'
                data-today='<?= date('Y-m-d') ?>'>
                <!-- Filas dinamicas se cargan aqui -->
              </tbody>
            </table>
          </div>

          <div class="p-3">
            <button type="button" class="btn btn-add-row w-100 py-3 fw-bold text-uppercase small"
              onclick="addNewPage()">
              <i class="fa-solid fa-plus-circle me-1"></i> Agregar Nueva URL al Sitemap
            </button>
          </div>
        </form>
      </div>

      <div class="card-footer">
        <p class="mb-0 small text-muted">
          <i class="fa-solid fa-circle-info me-1"></i>
          El archivo se guardara en la raiz de tu sitio como <code>sitemap.xml</code>. Google y otros buscadores lo
          leeran automaticamente.
        </p>
      </div>
    </div>

    <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
      <button type="submit" form="sitemapForm" class="btn btn-primary px-5 text-uppercase small fw-bold">
        <i class="fa-solid fa-floppy-disk me-2"></i>
        Guardar Cambios
      </button>
    </div>
  </div>
</div>