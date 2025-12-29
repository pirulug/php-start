<?php start_block('title'); ?>
Lista de Permisos
<?php end_block(); ?>

<?php start_block('css'); ?>
<style>
  /* Ajustes finos para la visualización de badges en dark mode */
  [data-bs-theme="dark"] .bg-body-secondary {
    background-color: rgba(255, 255, 255, 0.05) !important;
  }
</style>
<?php end_block(); ?>

<?php start_block('js'); ?>
<script>
  // Inicialización de Tooltips para los botones de acción
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>
<?php end_block(); ?>

<div class="row g-4">

  <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
    <div class="col-12 col-xl-6">

      <div class="card h-100">
        <!-- HEADER DEL GRUPO -->
        <div class="card-header bg-transparent py-3">
          <div class="d-flex align-items-center gap-2">
            <span
              class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle"
              style="width: 32px; height: 32px;">
              <i class="fa-solid fa-layer-group fs-6"></i>
            </span>
            <span class="fw-bold text-uppercase small">
              <?= htmlspecialchars($groupName) ?>
            </span>
          </div>
        </div>

        <div class="card-body p-0">

          <?php foreach ($contexts as $contextKey => $perms): ?>

            <!-- HEADER DEL CONTEXTO (Separador) -->
            <div class="px-3 py-2 bg-body-secondary d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-globe text-secondary small"></i>
                <span class="text-uppercase small fw-bold text-secondary">
                  <?= htmlspecialchars($perms[0]->permission_context_name) ?>
                </span>
              </div>
              <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">
                <?= count($perms) ?>
              </span>
            </div>

            <!-- LISTA DE PERMISOS -->
            <ul class="list-group list-group-flush">
              <?php foreach ($perms as $perm): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">

                  <div class="me-3 overflow-hidden">
                    <div class="fw-medium mb-1 text-truncate">
                      <?= htmlspecialchars($perm->permission_name) ?>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                      <span class="badge bg-secondary-subtle text-secondary-emphasis fw-normal font-monospace">
                        <i class="fa-solid fa-key me-1 opacity-50"></i>
                        <?= htmlspecialchars($perm->permission_key_name) ?>
                      </span>
                    </div>
                  </div>

                  <div class="d-flex gap-2">
                    <a href="<?= admin_route("permission/edit/" . $perm->permission_id) ?>"
                      class="btn btn-sm bg-primary-subtle text-primary" data-bs-toggle="tooltip" title="Editar">
                      <i class="fa fa-pen fa-fw"></i>
                    </a>

                    <button class="btn btn-sm bg-danger-subtle text-danger" sa-title="¿Eliminar permiso?"
                      sa-text="Esta acción no se puede deshacer." sa-icon="warning" sa-confirm-btn-text="Sí, eliminar"
                      sa-cancel-btn-text="No, cancelar"
                      sa-redirect-url="<?= admin_route("permission/delete/" . $perm->permission_id) ?>"
                      data-bs-toggle="tooltip" title="Eliminar">
                      <i class="fa fa-trash fa-fw"></i>
                    </button>
                  </div>

                </li>
              <?php endforeach; ?>
            </ul>

          <?php endforeach; ?>

        </div>
      </div>

    </div>
  <?php endforeach; ?>

</div>