<?php start_block('title'); ?>
Nuevo Permiso
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Permisos', 'link' => admin_route('permissions')],
  ['label' => 'Nuevo']
]) ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= admin_modules_script('security', 'permissions.new') ?>
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

    <!-- COLUMNA IZQUIERDA: GRUPO -->
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
          <p class="small text-muted mb-3">Define a qué grupo pertenecerá este nuevo permiso.</p>

          <div class="btn-group w-100 mb-3" role="group" aria-label="Modo de grupo">
            <input type="radio" class="btn-check" name="group_mode" id="mode_existing" value="existing" checked
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_existing">Existente</label>

            <input type="radio" class="btn-check" name="group_mode" id="mode_new" value="new"
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_new">Crear Nuevo</label>
          </div>

          <!-- MODO: EXISTENTE -->
          <div id="section_existing">
            <label for="group_name" class="form-label">Seleccionar Grupo</label>
            <select name="group_name" id="group_name" class="form-select" required>
              <option value="">-- Selecciona --</option>
              <?php foreach ($groups as $g): ?>
                <option value="<?= htmlspecialchars($g) ?>">
                  <?= htmlspecialchars($g) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text">El permiso se anidará bajo este grupo.</div>
          </div>

          <!-- MODO: NUEVO -->
          <div id="section_new" class="d-none">
            <div class="alert alert-info d-flex align-items-center mb-3 py-2" role="alert">
              <i class="fa-solid fa-circle-info me-2"></i>
              <small>Creando nueva categoría raíz.</small>
            </div>

            <div class="mb-3">
              <label for="new_group_name" class="form-label">Nombre del Grupo</label>
              <input type="text" class="form-control" name="new_group_name" id="new_group_name"
                placeholder="Ej: Reportes Financieros">
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- COLUMNA DERECHA: DETALLES -->
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

            <!-- Nombre -->
            <div class="col-12">
              <label for="permission_name" class="form-label">Nombre del Permiso <span
                  class="text-danger">*</span></label>
              <input type="text" class="form-control" name="permission_name" id="permission_name"
                placeholder="Ej: Exportar PDF" required>
            </div>

            <!-- Clave / Slug -->
            <div class="col-12">
              <label for="permission_key_name" class="form-label">Clave del Sistema <span
                  class="text-danger">*</span></label>
              <div class="input-group">
                <input type="text" class="form-control font-monospace bg-body" name="permission_key_name"
                  id="permission_key_name" placeholder="Ej: exportar.pdf" required readonly>
                <button class="btn btn-outline-secondary" type="button" onclick="unlockInput('permission_key_name')"
                  title="Editar manualmente">
                  <i class="fa-solid fa-pen"></i>
                </button>
              </div>
              <div class="form-text">Identificador único utilizado por el backend y la API.</div>
            </div>

            <!-- Contexto -->
            <div class="col-12 col-lg-6">
              <label for="context" class="form-label">Contexto de Aplicación <span class="text-danger">*</span></label>
              <select name="context" id="context" class="form-select" required>
                <option value="admin">Administrador (Admin)</option>
                <option value="front">Público (Front)</option>
              </select>
            </div>

            <!-- Descripción -->
            <div class="col-12">
              <label for="permission_description" class="form-label">Descripción Opcional</label>
              <textarea class="form-control" name="permission_description" id="permission_description" rows="3"
                placeholder="Detalles técnicos o funcionales sobre lo que habilita este permiso..."></textarea>
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
          <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Permiso
        </button>
      </div>
    </div>

  </div>
</form>