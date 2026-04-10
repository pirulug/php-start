<?php start_block("title"); ?>
Sistema y Rendimiento
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Sistema']
]) ?>
<?php end_block(); ?>

<div class="row">

  <div class="col-lg-8">
    <form action="" method="post">
      <div class="card mb-4">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-microchip me-2"></i>Ajustes de Sistema</h6>
          
          <div class="row mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Loader Dashboard (Admin)</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="loader_admin" value="true" id="loader_admin" <?= ($options->loader_admin === 'true') ? 'checked' : '' ?>>
                <label class="form-check-label" for="loader_admin">Activar en el panel</label>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Loader Home (Frontend)</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="loader_home" value="true" id="loader_home" <?= ($options->loader_home === 'true') ? 'checked' : '' ?>>
                <label class="form-check-label" for="loader_home">Activar en la web</label>
              </div>
            </div>
          </div>

          <hr>

          <div class="mb-3">
            <h6 class="text-danger fw-bold mb-3"><i class="fa-solid fa-wrench me-2"></i>Modo Mantenimiento</h6>
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode" <?= file_exists(BASE_DIR . '/MAINTENANCE') ? 'checked' : '' ?>>
              <label class="form-check-label" for="maintenance_mode">Activar modo mantenimiento</label>
            </div>
            
            <label class="form-label small">Mensaje para visitantes:</label>
            <textarea name="maintenance_msg" class="form-control" rows="2"><?= $options->site_maintenance_msg ?? 'Estamos trabajando en mejoras. Volvemos pronto.' ?></textarea>
            <div class="form-text">Si está activo, los usuarios verán este mensaje y no podrán acceder al sitio.</div>
          </div>

        </div>
        <div class="card-footer d-flex justify-content-end py-3">
          <button class="btn btn-primary px-4 text-uppercase small fw-bold">
            <i class="fa-solid fa-save me-1"></i> Guardar Cambios
          </button>
        </div>
      </div>
    </form>
  </div>

  <div class="col-lg-4">
    <div class="card border-warning mb-4">
      <div class="card-body">
        <h6 class="text-warning fw-bold mb-3"><i class="fa-solid fa-rocket me-2"></i>Rendimiento</h6>
        <p class="small text-muted">La purga de caché eliminará todos los archivos temporales de rutas y configuración generados por el framework.</p>
        
        <form action="" method="POST" onsubmit="return confirm('¿Estás seguro de purgar la caché?')">
           <input type="hidden" name="action" value="clear_cache">
           <button class="btn btn-outline-warning w-100 fw-bold">
             <i class="fa-solid fa-trash-can me-1"></i> PURGAR CACHÉ
           </button>
        </form>
      </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-circle-info me-2"></i>Estado del Servidor</h6>
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2 d-flex justify-content-between">
                    <span>Versión PHP:</span>
                    <span class="fw-bold"><?= PHP_VERSION ?></span>
                </li>
                <li class="mb-2 d-flex justify-content-between">
                    <span>Max Upload:</span>
                    <span class="fw-bold"><?= ini_get('upload_max_filesize') ?></span>
                </li>
                <li class="mb-0 d-flex justify-content-between">
                    <span>Memoria Límite:</span>
                    <span class="fw-bold"><?= ini_get('memory_limit') ?></span>
                </li>
            </ul>
        </div>
    </div>
  </div>

</div>