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
  /* Ajustes finos para la visualización de badges en dark mode */
  [data-bs-theme="dark"] .bg-body-secondary {
    background-color: rgba(255, 255, 255, 0.05) !important;
  }

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

  .permission-check:checked ~ .permission-info {
    opacity: 0.7;
  }
</style>
<?php end_block(); ?>

<?php start_block('js'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    const bulkBar = document.getElementById('bulk-bar');
    const selectedCount = document.getElementById('selected-count');
    const checkboxes = document.querySelectorAll('.permission-check');
    const selectAllCheckboxes = document.querySelectorAll('.select-all-group');

    function updateBulkBar() {
      const checkedBoxes = document.querySelectorAll('.permission-check:checked');
      const count = checkedBoxes.length;
      
      if (count > 0) {
        selectedCount.textContent = count;
        bulkBar.classList.add('show');
      } else {
        bulkBar.classList.remove('show');
        // Reset new group field if bar hides
        resetBulkNewGroup();
      }
    }

    // Toggle checkboxes
    checkboxes.forEach(cb => {
      cb.addEventListener('change', updateBulkBar);
    });

    // Select All logic
    selectAllCheckboxes.forEach(toggler => {
      toggler.addEventListener('change', function() {
        const target = this.getAttribute('data-target');
        const listItems = document.querySelectorAll(`[data-group="${target}"] .permission-check`);
        listItems.forEach(cb => {
          cb.checked = this.checked;
        });
        updateBulkBar();
      });
    });

    // Bulk New Group Toggle
    const groupSelect = document.querySelector('select[name="new_group_id"]');
    const newGroupSection = document.getElementById('bulk-new-group-section');
    const newGroupNameInput = document.getElementById('bulk_new_group_name');

    groupSelect.addEventListener('change', function() {
      if (this.value === "-1") {
        newGroupSection.classList.remove('d-none');
        newGroupNameInput.focus();
      } else {
        newGroupSection.classList.add('d-none');
      }
    });

    function resetBulkNewGroup() {
      groupSelect.value = "0";
      newGroupSection.classList.add('d-none');
      newGroupNameInput.value = "";
      document.getElementById('bulk_new_group_key').value = "";
    }

    // Slug Generator for Bulk Bar
    window.generateBulkSlug = function(text, targetId) {
      const slug = text.toString().toLowerCase()
        .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, '.')
        .replace(/[^\w\.]+/g, '')
        .replace(/\.\.+/g, '.')
        .replace(/^\.+/, '')
        .replace(/\.+$/, '');

      const target = document.getElementById(targetId);
      if (target) target.value = slug;
    }
  });
</script>
<?php end_block(); ?>

<form action="<?= admin_route("permissions") ?>" method="POST" id="bulk-permissions-form">
  <input type="hidden" name="bulk_action" value="move_group">

  <div class="row g-4 mb-5">
    <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
      <?php $groupIdentifier = str_replace(' ', '_', strtolower($groupName)); ?>
      <div class="col-12 col-xl-6" data-group="main-<?= $groupIdentifier ?>">

        <div class="card h-100">
          <!-- HEADER DEL GRUPO -->
          <div class="card-header bg-transparent py-3">
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
                <input class="form-check-input select-all-group" type="checkbox" data-target="main-<?= $groupIdentifier ?>">
              </div>
            </div>
          </div>

          <div class="card-body p-0">

            <?php foreach ($contexts as $contextKey => $perms): ?>
              <?php $contextIdentifier = $groupIdentifier . '-' . str_replace(' ', '_', strtolower($contextKey)); ?>

              <!-- HEADER DEL CONTEXTO (Separador) -->
              <div class="px-3 py-2 bg-body-secondary d-flex justify-content-between align-items-center" data-group="ctx-<?= $contextIdentifier ?>">
                <div class="d-flex align-items-center gap-2">
                  <div class="form-check mb-0">
                    <input class="form-check-input select-all-group" type="checkbox" data-target="ctx-<?= $contextIdentifier ?>">
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
              <ul class="list-group list-group-flush" data-group="main-<?= $groupIdentifier ?>">
                <div data-group="ctx-<?= $contextIdentifier ?>">
                <?php foreach ($perms as $perm): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">
                    
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                      <div class="form-check mb-0">
                        <input class="form-check-input permission-check" type="checkbox" name="permissions[]" value="<?= $perm->permission_id ?>">
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
                        </div>
                      </div>
                    </div>

                    <div class="d-flex gap-2">
                      <a href="<?= admin_route("permission/edit/" . $perm->permission_id) ?>"
                        class="btn btn-sm bg-primary-subtle text-primary" data-bs-toggle="tooltip" title="Editar">
                        <i class="fa fa-pen fa-fw"></i>
                      </a>

                      <button type="button" class="btn btn-sm bg-danger-subtle text-danger" sa-title="¿Eliminar permiso?"
                        sa-text="Esta acción no se puede deshacer." sa-icon="warning" sa-confirm-btn-text="Sí, eliminar"
                        sa-cancel-btn-text="No, cancelar"
                        sa-redirect-url="<?= admin_route("permission/delete/" . $perm->permission_id) ?>"
                        data-bs-toggle="tooltip" title="Eliminar">
                        <i class="fa fa-trash fa-fw"></i>
                      </button>
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
    <div class="card border border-primary border-opacity-25 bg-body-tertiary">
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
                <option value="<?= $group->permission_group_id ?>"><?= htmlspecialchars($group->permission_group_name) ?></option>
              <?php endforeach; ?>
              <option value="-1" class="fw-bold text-primary">+ Crear nuevo grupo...</option>
            </select>
          </div>

          <div id="bulk-new-group-section" class="d-none animate__animated animate__fadeIn">
            <div class="d-flex align-items-center gap-2">
              <input type="text" name="bulk_new_group_name" id="bulk_new_group_name" class="form-select form-select-sm" 
                placeholder="Nombre del grupo" onkeyup="generateBulkSlug(this.value, 'bulk_new_group_key')">
              <input type="hidden" name="bulk_new_group_key" id="bulk_new_group_key">
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary btn-sm px-3">
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