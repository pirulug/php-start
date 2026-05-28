<?php start_block('title'); ?>
Editar Permiso
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Permisos', 'link' => admin_route('permissions')],
  ['label' => 'Editar']
]) ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= admin_modules_script('security', 'permissions.edit') ?>
<script>
  function toggleGroupMode() {
    const isNew = document.getElementById('mode_new').checked;
    document.getElementById('section_existing').classList.toggle('d-none', isNew);
    document.getElementById('section_new').classList.toggle('d-none', !isNew);
    
    if (isNew) {
      document.getElementById('group_name').removeAttribute('required');
      document.getElementById('new_group_name').setAttribute('required', 'required');
    } else {
      document.getElementById('group_name').setAttribute('required', 'required');
      document.getElementById('new_group_name').removeAttribute('required');
    }
  }

  function unlockInput(id) {
    const input = document.getElementById(id);
    input.removeAttribute('readonly');
    input.focus();
    input.select();
  }
</script>
<?php end_block(); ?>

<form method="POST" action="" autocomplete="off" class="needs-validation">

  <div class="row g-3">

    <!-- COLUMNA IZQUIERDA -->
    <div class="col-12 col-md-5 col-xl-4">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <span
              class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle"
              style="width: 32px; height: 32px;">
              <i class="fa-solid fa-layer-group fs-6"></i>
            </span>
            Asignación
          </h5>
        </div>

        <div class="card-body">
          <div class="btn-group w-100 mb-3" role="group">
            <input type="radio" class="btn-check" name="group_mode" id="mode_existing" value="existing" checked
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_existing">Existente</label>

            <input type="radio" class="btn-check" name="group_mode" id="mode_new" value="new"
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_new">Mover a Nuevo</label>
          </div>

          <div id="section_existing">
            <label for="group_name" class="form-label">Seleccionar Grupo</label>
            <select name="group_name" id="group_name" class="form-select" required>
              <?php foreach ($groups as $g): ?>
                <option value="<?= htmlspecialchars($g) ?>" <?= ($permission->group_name == $g) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($g) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div id="section_new" class="d-none">
            <div class="mb-3">
              <label for="new_group_name" class="form-label">Nombre del Nuevo Grupo</label>
              <input type="text" class="form-control" name="new_group_name" id="new_group_name"
                placeholder="Ej: Nueva Categoría">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="col-12 col-md-7 col-xl-8">
      <div class="card mb-3">
        <div class="card-header">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <span
              class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle"
              style="width: 32px; height: 32px;">
              <i class="fa-solid fa-shield-halved fs-6"></i>
            </span>
            Detalles del Permiso
          </h5>
        </div>

        <div class="card-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="permission_name" class="form-label">Nombre del Permiso</label>
              <input type="text" class="form-control" name="permission_name" id="permission_name"
                value="<?= htmlspecialchars($permission->permission_name) ?>" required>
            </div>

            <div class="col-12">
              <label for="permission_key_name" class="form-label">Clave del Sistema</label>
              <div class="input-group">
                <input type="text" class="form-control font-monospace bg-body" name="permission_key_name"
                  id="permission_key_name" value="<?= htmlspecialchars($permission->permission_key_name) ?>" required
                  readonly>
                <button class="btn btn-outline-secondary" type="button" onclick="unlockInput('permission_key_name')">
                  <i class="fa-solid fa-pen"></i>
                </button>
              </div>
            </div>

            <div class="col-12 col-lg-6">
              <label for="context" class="form-label">Contexto</label>
              <select name="context" id="context" class="form-select" required>
                <option value="admin" <?= ($permission->context == 'admin') ? 'selected' : '' ?>>Administrador</option>
                <option value="front" <?= ($permission->context == 'front') ? 'selected' : '' ?>>Front-end</option>
              </select>
            </div>

            <div class="col-12">
              <label for="permission_description" class="form-label">Descripción</label>
              <textarea class="form-control" name="permission_description" id="permission_description"
                rows="3"><?= htmlspecialchars($permission->permission_description) ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- BOTONERA -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
        <a href="<?= admin_route("permissions") ?>" class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
          <i class="fa-solid fa-arrow-left me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Cambios
        </button>
      </div>
    </div>

  </div>
</form>