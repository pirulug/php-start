<?php start_block('title'); ?>
Editar Rol
<?php end_block(); ?>

<?php start_block('css'); ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<script>
  /**
   * Permite marcar/desmarcar todo un grupo visualmente.
   * UX Vital para roles con muchos permisos.
   */
  function toggleGroup(groupId) {
    const container = document.getElementById(groupId);
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');

    // Lógica inteligente: Si hay alguno desmarcado, los marca todos. Si todos están marcados, los desmarca.
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => {
      cb.checked = !allChecked;
    });
  }
</script>
<?php end_block(); ?>

<form method="POST" autocomplete="off">

  <div class="row g-4">

    <div class="col-12 col-md-4 col-xl-3">
      <div class="card h-100" style="position: sticky; top: 1rem;">

        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-user-gear text-primary"></i>
            Datos del Rol
          </h5>
        </div>

        <div class="card-body">
          <div class="mb-4">
            <label class="form-label text-muted small text-uppercase fw-bold">Nombre del Rol <span
                class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="fa-solid fa-signature"></i></span>
              <input type="text" name="role_name" class="form-control"
                value="<?= htmlspecialchars($role->role_name ?? "") ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label text-muted small text-uppercase fw-bold">Descripción</label>
            <textarea class="form-control" name="role_description" rows="6"
              placeholder="Describe la función de este rol..."><?= htmlspecialchars($role->role_description ?? "") ?></textarea>
          </div>
        </div>

      </div>
    </div>

    <div class="col-12 col-md-8 col-xl-9">
      <div class="card h-100">

        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-shield-halved text-success"></i>
            Permisos Asignados
          </h5>
          <?php if (isset($assigned_permissions)): ?>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
              <?= count($assigned_permissions) ?> activos
            </span>
          <?php endif; ?>
        </div>

        <div class="card-body">

          <?php foreach ($groupedPermissions as $groupName => $contexts): ?>
            <div class="mb-5">

              <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-layer-group text-secondary"></i>
                <h6 class="mb-0 text-uppercase fw-bold">
                  <?= htmlspecialchars($groupName) ?>
                </h6>
              </div>

              <?php foreach ($contexts as $contextKey => $perms): ?>
                <div class="border rounded mb-3">

                  <div class="px-3 py-2 bg-light d-flex justify-content-between align-items-center">
                    <span class="badge bg-secondary text-uppercase">
                      <?= htmlspecialchars($perms[0]->permission_context_name) ?>
                    </span>

                    <button type="button" class="btn btn-sm btn-link text-decoration-none"
                      onclick="toggleGroup('ctx-<?= md5($groupName . $contextKey) ?>')">
                      <i class="fa-solid fa-check-double me-1"></i>Alternar contexto
                    </button>
                  </div>

                  <div class="row g-3 p-3" id="ctx-<?= md5($groupName . $contextKey) ?>">
                    <?php foreach ($perms as $perm): ?>
                      <div class="col-12 col-md-6 col-lg-4">

                        <div class="form-check p-3 rounded h-100 bg-secondary bg-opacity-10 d-flex align-items-start gap-2">
                          <input id="perm-<?= $perm->permission_id ?>" class="form-check-input mt-1" type="checkbox"
                            name="permissions[]" value="<?= $perm->permission_id ?>" <?= in_array($perm->permission_id, $assigned_permissions) ? 'checked' : '' ?>>

                          <label class="form-check-label w-100" for="perm-<?= $perm->permission_id ?>">
                            <span class="d-block fw-bold text-body">
                              <?= htmlspecialchars($perm->permission_name) ?>
                            </span>
                            <span class="d-block text-muted small font-monospace mt-1">
                              <?= htmlspecialchars($perm->permission_key_name) ?>
                            </span>
                          </label>
                        </div>

                      </div>
                    <?php endforeach; ?>
                  </div>

                </div>
              <?php endforeach; ?>

            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="d-flex justify-content-end gap-2 pt-2 pb-4">
        <a href="roles.php" class="btn btn-outline-secondary px-4">
          Cancelar
        </a>
        <button type="submit" class="btn btn-primary px-5">
          <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios
        </button>
      </div>
    </div>

  </div>
</form>

