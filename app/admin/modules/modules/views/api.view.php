<?php block_start("title") ?>
  Módulos de API Global
<?php block_end() ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Módulos"]
]) ?>
<?php block_end(); ?>

<?php block_start("css") ?>
<style>
  .module-list-group .list-group-item {
    transition: background-color 0.2s ease, border-color 0.2s ease;
    border-color: rgba(0, 0, 0, 0.08);
  }
  [data-bs-theme="dark"] .module-list-group .list-group-item {
    border-color: rgba(255, 255, 255, 0.08);
  }
</style>
<?php block_end() ?>

<form action="<?= admin_route("modules/api") ?>" method="POST" id="modules-form">
  <div class="card mb-3">
    <div class="card-header bg-transparent border-bottom py-3">
      <?php require_once BASE_DIR . "/app/admin/modules/modules/views/partials/nav.partial.php"; ?>
    </div>
    
    <div class="card-body p-3">
      <div class="mb-3 border-bottom pb-2">
        <h5 class="card-title mb-1">Módulos de la API Global</h5>
        <p class="text-muted small mb-0">Habilita o deshabilita los módulos expuestos para los endpoints de la API.</p>
      </div>
      
      <div class="list-group module-list-group">
        <?php foreach ($view_api_modules as $name => $info): ?>
          <div class="list-group-item d-flex align-items-center justify-content-between p-3">
            <div>
              <h6 class="mb-0 text-uppercase fw-bold">
                <?= clear_html($name) ?>
                <?php if ($info["protected"]): ?>
                  <i class="fa-solid fa-lock text-warning small ms-2" title="Módulo del sistema protegido"></i>
                <?php endif; ?>
              </h6>
              <span class="text-muted small">Ubicado en /app/api/<?= clear_html($name) ?></span>
            </div>
            
            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
              <input class="form-check-input" type="checkbox" role="switch" name="api_active[<?= clear_html($name) ?>]" id="switch_api_<?= clear_html($name) ?>" <?= $info["active"] ? "checked" : "" ?> <?= $info["protected"] ? "disabled" : "" ?>>
              <span class="small text-muted">Activo</span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
    <a href="<?= admin_route("dashboard") ?>" class="btn btn-outline-secondary px-4 text-uppercase fw-bold">
      <i class="fa-solid fa-arrow-left me-2"></i>
      Volver
    </a>
    <button type="submit" class="btn btn-primary px-5 text-uppercase fw-bold">
      <i class="fa-solid fa-floppy-disk me-2"></i>
      Guardar Cambios
    </button>
  </div>
</form>
