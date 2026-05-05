<?php start_block('title'); ?>
Listar Permisos
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Permisos']
]) ?>
<?php end_block(); ?>

<?php start_block('css'); ?>
<style>
  /* Floating Bulk Bar */
  .bulk-action-bar {
    position: fixed;
    bottom: -100px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1050;
    transition: bottom 0.3s ease-in-out;
    width: auto;
    max-width: 90%;
  }

  .bulk-action-bar.show {
    bottom: 25px;
  }

  .permission-check:checked~.permission-info {
    opacity: 0.7;
  }
</style>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= url_script_admin('security', 'permissions.list') ?>
<?php end_block(); ?>

<div class="bg-body p-3 rounded mb-3 text-end">
  <a href="<?= admin_route("permission/new") ?>" class="btn btn-primary px-4 text-uppercase small fw-bold text-nowrap">
    <i class="fa-solid fa-plus me-2"></i> Nuevo Permiso
  </a>
</div>

<form action="<?= admin_route("permissions") ?>" method="POST" id="bulk-permissions-form">
  <input type="hidden" name="bulk_action" value="move_group">

  <div class="row g-3">
    <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
      <?php $groupIdentifier = str_replace(' ', '_', strtolower($groupName)); ?>
      <div class="col-12 col-xl-6" data-group="main-<?= $groupIdentifier ?>">

        <div class="card h-100">
          <!-- HEADER DEL GRUPO -->
          <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
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
              <div class="form-check form-switch mb-0">
                <input class="form-check-input select-all-group" type="checkbox"
                  data-target="main-<?= $groupIdentifier ?>">
              </div>
            </div>
          </div>

          <div class="card-body">

            <?php foreach ($contexts as $contextKey => $perms): ?>
              <?php $contextIdentifier = $groupIdentifier . '-' . str_replace(' ', '_', strtolower($contextKey)); ?>

              <!-- HEADER DEL CONTEXTO (Separador) -->
              <div class="px-3 py-2 bg-body d-flex justify-content-between align-items-center border rounded mb-2"
                data-group="ctx-<?= $contextIdentifier ?>">
                <div class="d-flex align-items-center gap-2">
                  <div class="form-check mb-0">
                    <input class="form-check-input select-all-group" type="checkbox"
                      data-target="ctx-<?= $contextIdentifier ?>">
                  </div>
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
              <ul class="list-group list-group-flush mb-3" data-group="main-<?= $groupIdentifier ?>">
                <div data-group="ctx-<?= $contextIdentifier ?>">
                  <?php foreach ($perms as $perm): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">

                      <div class="d-flex align-items-center gap-3 overflow-hidden">
                        <div class="form-check mb-0">
                          <input class="form-check-input permission-check" type="checkbox" name="permissions[]"
                            value="<?= $perm->permission_id ?>">
                        </div>

                        <div class="permission-info text-truncate">
                          <div class="fw-medium mb-1 text-truncate">
                            <?= htmlspecialchars($perm->permission_name) ?>
                          </div>

                          <div class="d-flex align-items-center gap-2 flex-wrap text-truncate">
                            <span class="badge bg-secondary-subtle text-secondary-emphasis fw-normal font-monospace">
                              <i class="fa-solid fa-key me-1 opacity-50"></i>
                              <?= htmlspecialchars($perm->permission_key_name) ?>
                            </span>
                            <?php if ($perm->permission_key_name === 'access.admin'): ?>
                              <span class="badge bg-primary bg-opacity-10 text-primary small text-uppercase">
                                <i class="fa-solid fa-shield-halved me-1"></i> Gatekeeper
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>

                      <div class="d-flex gap-2">
                        <?php if ($perm->permission_key_name !== 'access.admin'): ?>
                          <a href="<?= admin_route("permission/edit/" . $perm->permission_id) ?>"
                            class="btn btn-sm btn-outline-primary text-uppercase small fw-bold" data-bs-toggle="tooltip"
                            title="Editar">
                            <i class="fa fa-pen fa-fw"></i>
                          </a>

                          <button type="button" class="btn btn-sm btn-outline-danger text-uppercase small fw-bold"
                            sa-title="¿Eliminar permiso?" sa-text="Esta acción no se puede deshacer." sa-icon="warning"
                            sa-confirm-btn-text="Sí, eliminar" sa-cancel-btn-text="No, cancelar"
                            sa-redirect-url="<?= admin_route("permission/delete/" . $perm->permission_id) ?>"
                            data-bs-toggle="tooltip" title="Eliminar">
                            <i class="fa fa-trash fa-fw"></i>
                          </button>
                        <?php else: ?>
                          <span class="badge bg-secondary-subtle text-secondary small text-uppercase">
                            <i class="fa-solid fa-lock me-1"></i> Sistema
                          </span>
                        <?php endif; ?>
                      </div>

                    </li>
                  <?php endforeach; ?>
                </div>
              </ul>

            <?php endforeach; ?>

          </div>
        </div>

      </div>
    <?php endforeach; ?>
  </div>

  <!-- FLOATING BULK BAR -->
  <div class="bulk-action-bar" id="bulk-bar">
    <div class="card border border-primary border-opacity-25 bg-body">
      <div class="card-body py-2 px-3">
        <div class="d-flex align-items-center gap-3">
          <div class="d-none d-md-block">
            <span class="badge bg-primary rounded-pill me-1" id="selected-count">0</span>
            <span class="text-secondary small fw-bold text-uppercase">Seleccionados</span>
          </div>

          <div class="vr d-none d-md-block"></div>

          <div class="d-flex align-items-center gap-2">
            <label class="small text-secondary fw-bold text-uppercase d-none d-sm-block">Mover a:</label>
            <select name="new_group_id" class="form-select form-select-sm" style="min-width: 170px;">
              <option value="0" disabled selected>Seleccionar grupo...</option>
              <?php foreach ($allGroups as $group): ?>
                <option value="<?= htmlspecialchars($group) ?>"><?= htmlspecialchars($group) ?></option>
              <?php endforeach; ?>

              <option value="-1" class="fw-bold text-primary">+ Crear nuevo grupo...</option>
            </select>
          </div>

          <div id="bulk-new-group-section" class="d-none animate__animated animate__fadeIn">
            <div class="d-flex align-items-center gap-2">
              <input type="text" name="bulk_new_group_name" id="bulk_new_group_name" class="form-control form-control-sm"
                placeholder="Nombre del grupo" onkeyup="generateBulkSlug(this.value, 'bulk_new_group_key')">
              <input type="hidden" name="bulk_new_group_key" id="bulk_new_group_key">
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-sm px-3 text-uppercase small fw-bold">
            <i class="fa-solid fa-right-left me-1"></i> Mover
          </button>

          <button type="button" class="btn btn-link btn-sm text-secondary p-0 ms-1" onclick="resetBulkNewGroup()">
            <i class="fa-solid fa-times"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

</form>