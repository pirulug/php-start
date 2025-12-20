<form method="POST" action="" autocomplete="off">

  <div class="row g-4">

    <div class="col-12 col-md-5 col-xl-4">
      <div class="card h-100">
        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-layer-group text-primary"></i>
            Asignación de Grupo
          </h5>
        </div>

        <div class="card-body">
          <div class="btn-group w-100 mb-4" role="group">
            <input type="radio" class="btn-check" name="group_mode" id="mode_existing" value="existing" checked
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_existing">Existente</label>

            <input type="radio" class="btn-check" name="group_mode" id="mode_new" value="new"
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_new">Crear Nuevo</label>
          </div>

          <div id="section_existing">
            <label class="form-label small text-muted text-uppercase fw-bold">Grupo</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-list"></i></span>
              <select name="group_id" id="group_id" class="form-select">
                <option value="">-- Selecciona --</option>
                <?php foreach ($groups as $g): ?>
                  <option value="<?= htmlspecialchars($g->permission_group_id) ?>">
                    <?= htmlspecialchars($g->permission_group_name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-text">El permiso se agregará a este grupo.</div>
          </div>

          <div id="section_new" class="d-none">
            <div class="alert alert-light border d-flex align-items-center mb-3" role="alert">
              <i class="fa-solid fa-plus-circle text-success me-2"></i>
              <small>Estás creando una nueva categoría.</small>
            </div>

            <div class="mb-3">
              <label for="new_group_name" class="form-label small text-muted text-uppercase fw-bold">Nombre
                Grupo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                <input type="text" class="form-control" name="new_group_name" id="new_group_name"
                  placeholder="Ej: Reportes" onkeyup="generateSlug(this.value, 'new_group_key')">
              </div>
            </div>

            <div class="mb-3">
              <label for="new_group_key" class="form-label small text-muted text-uppercase fw-bold">Clave (Slug)</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-key text-muted"></i></span>
                <input type="text" class="form-control bg-light" name="new_group_key" id="new_group_key"
                  placeholder="ej: reportes" readonly tabindex="-1">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-12 col-md-7 col-xl-8">
      <div class="card h-100">
        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-shield-halved text-success"></i>
            Detalles del Permiso
          </h5>
        </div>

        <div class="card-body">
          <div class="row g-3">

            <div class="col-12">
              <label for="permission_name" class="form-label">Nombre del Permiso <span
                  class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-font"></i></span>
                <input type="text" class="form-control" name="permission_name" id="permission_name"
                  placeholder="Ej: Ver Dashboard" required onkeyup="generateSlug(this.value, 'permission_key_name')">
              </div>
            </div>

            <div class="col-12">
              <label for="permission_key_name" class="form-label">Clave del Permiso (Sistema) <span
                  class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-code"></i></span>
                <input type="text" class="form-control font-monospace" name="permission_key_name"
                  id="permission_key_name" placeholder="Ej: dashboard_view" required>
                <button class="btn btn-outline-secondary" type="button" onclick="unlockInput('permission_key_name')"
                  title="Editar manualmente">
                  <i class="fa-solid fa-pen"></i>
                </button>
              </div>
              <div class="form-text">Se genera automáticamente, pero puedes editarlo si es necesario.</div>
            </div>

            <div class="col-12">
              <label for="permission_description" class="form-label">Descripción</label>
              <textarea class="form-control" name="permission_description" id="permission_description" rows="3"
                placeholder="Describe brevemente qué permite hacer este permiso..."></textarea>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="bg-body p-3 rounded">
        <div class="d-flex justify-content-end gap-2">
          <a href="<?= url_admin("permissions") ?>" class="btn btn-outline-secondary px-4">
            Cancelar
          </a>
          <button type="submit" class="btn btn-primary px-4">
            <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Permiso
          </button>
        </div>
      </div>
    </div>

  </div>
</form>

<script>
  // 1. Lógica para mostrar/ocultar secciones (Tabs)
  function toggleGroupMode() {
    const isNew = document.getElementById('mode_new').checked;
    const sectionExisting = document.getElementById('section_existing');
    const sectionNew = document.getElementById('section_new');

    // Inputs a resetear/deshabilitar para evitar envío de datos sucios
    const selectExisting = document.getElementById('group_id');
    const inputNewName = document.getElementById('new_group_name');

    if (isNew) {
      sectionExisting.classList.add('d-none');
      sectionNew.classList.remove('d-none');
      selectExisting.value = ""; // Reset select
      inputNewName.required = true;
    } else {
      sectionExisting.classList.remove('d-none');
      sectionNew.classList.add('d-none');
      inputNewName.value = ""; // Reset input
      inputNewName.required = false;
    }
  }

  // 2. Lógica para Auto-Slug (Nombre -> Clave)
  function generateSlug(text, targetId) {
    const slug = text.toString().toLowerCase()
      .replace(/\s+/g, '_')           // Espacios a guiones bajos
      .replace(/[^\w\-]+/g, '')       // Eliminar caracteres no permitidos
      .replace(/\-\-+/g, '_')         // Reemplazar múltiples guiones
      .replace(/^-+/, '')             // Trim inicio
      .replace(/-+$/, '');            // Trim final

    const target = document.getElementById(targetId);
    // Solo actualizamos si el usuario no ha editado manualmente o si está vacío
    // (Lógica simplificada: siempre sobrescribe mientras escribe el nombre)
    target.value = slug;
  }

  // 3. Desbloquear input de slug si el usuario quiere editarlo
  function unlockInput(id) {
    document.getElementById(id).focus();
  }
</script>