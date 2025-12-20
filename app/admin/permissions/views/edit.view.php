<form method="POST" action="" autocomplete="off">

  <div class="row g-4">

    <div class="col-12 col-md-5 col-xl-4">
      <div class="card h-100">
        <div class="card-header  py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-layer-group text-primary"></i>
            Grupo del Permiso
          </h5>
        </div>

        <div class="card-body">
          <div class="btn-group w-100 mb-4" role="group">
            <input type="radio" class="btn-check" name="group_mode" id="mode_existing" value="existing" checked
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_existing">Seleccionar</label>

            <input type="radio" class="btn-check" name="group_mode" id="mode_new" value="new"
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_new">Crear Nuevo</label>
          </div>

          <div id="section_existing">
            <label class="form-label small text-muted text-uppercase fw-bold">Grupo Actual</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-list"></i></span>
              <select name="group_id" id="group_id" class="form-select">
                <option value="">-- Selecciona --</option>
                <?php foreach ($groups as $g): ?>
                  <option value="<?= $g->permission_group_id ?>"
                    <?= $g->permission_group_id == $permission->permission_group_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g->permission_group_name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-text">Cambia el grupo para mover este permiso.</div>
          </div>

          <div id="section_new" class="d-none">
            <div class="alert alert-info border-0 d-flex align-items-center mb-3 py-2" role="alert">
              <i class="fa-solid fa-info-circle me-2"></i>
              <small>El permiso se moverá a este nuevo grupo.</small>
            </div>

            <div class="mb-3">
              <label class="form-label small text-muted text-uppercase fw-bold">Nombre Nuevo Grupo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                <input type="text" class="form-control" name="new_group_name" id="new_group_name"
                  placeholder="Ej: Reportes" onkeyup="generateSlug(this.value, 'new_group_key')">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label small text-muted text-uppercase fw-bold">Clave (Slug)</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                <input type="text" class="form-control" name="new_group_key" id="new_group_key"
                  placeholder="ej: reportes" readonly>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-12 col-md-7 col-xl-8">
      <div class="card h-100">
        <div class="card-header  py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-success"></i>
            Editar Datos
          </h5>
        </div>

        <div class="card-body">

          <div class="mb-3">
            <label for="permission_name" class="form-label">Nombre del Permiso</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-font"></i></span>
              <input type="text" class="form-control" name="permission_name" id="permission_name"
                value="<?= htmlspecialchars($permission->permission_name) ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="permission_key_name" class="form-label">Clave del Permiso (Slug)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-code"></i></span>
              <input type="text" class="form-control font-monospace" name="permission_key_name" id="permission_key_name"
                value="<?= htmlspecialchars($permission->permission_key_name) ?>" required readonly>
              <button class="btn btn-outline-secondary" type="button" onclick="unlockInput('permission_key_name')"
                title="Habilitar edición">
                <i class="fa-solid fa-lock-open"></i>
              </button>
            </div>
            <div class="form-text text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> Editar la clave
              puede romper la funcionalidad del sistema.</div>
          </div>

          <div class="mb-3">
            <label for="permission_description" class="form-label">Descripción</label>
            <textarea class="form-control" name="permission_description" id="permission_description"
              rows="3"><?= htmlspecialchars($permission->permission_description) ?></textarea>
          </div>

        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="bg-body p-3 rounded">
        <div class=" d-flex justify-content-end pt-2 gap-2">
          <a href="<?= url_admin("permissions") ?>" class="btn btn-outline-secondary px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Volver
          </a>
          <button type="submit" class="btn btn-primary px-4">
            <i class="fa-solid fa-rotate me-2"></i>Actualizar
          </button>
        </div>
      </div>
    </div>

  </div>
</form>

<script>
  // 1. Alternar entre Grupo Existente / Nuevo
  function toggleGroupMode() {
    const isNew = document.getElementById('mode_new').checked;
    const sectionExisting = document.getElementById('section_existing');
    const sectionNew = document.getElementById('section_new');

    // Inputs a limpiar
    const selectExisting = document.getElementById('group_id');
    const inputNewName = document.getElementById('new_group_name');

    if (isNew) {
      sectionExisting.classList.add('d-none');
      sectionNew.classList.remove('d-none');
      // No reseteamos selectExisting aquí para no perder el dato original si se arrepiente, 
      // pero el backend debe priorizar "new_group_name" si no está vacío.
      inputNewName.required = true;
    } else {
      sectionExisting.classList.remove('d-none');
      sectionNew.classList.add('d-none');
      inputNewName.value = "";
      inputNewName.required = false;
    }
  }

  // 2. Generar Slug automático
  function generateSlug(text, targetId) {
    const slug = text.toString().toLowerCase()
      .replace(/\s+/g, '_')
      .replace(/[^\w\-]+/g, '')
      .replace(/\-\-+/g, '_')
      .replace(/^-+/, '')
      .replace(/-+$/, '');
    document.getElementById(targetId).value = slug;
  }

  // 3. Desbloquear edición de la Key
  function unlockInput(id) {
    const input = document.getElementById(id);
    if (input.readOnly) {
      input.readOnly = false;
      input.focus();
      input.classList.remove('bg-light'); // Si tuviera fondo gris
    } else {
      input.readOnly = true;
    }
  }
</script>