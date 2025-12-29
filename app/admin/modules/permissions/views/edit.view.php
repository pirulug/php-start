<?php start_block('title'); ?>
Editar Permiso
<?php end_block(); ?>

<?php start_block('css'); ?>
<style>
  /* Estilos para inputs readonly en modo oscuro/claro */
  .form-control[readonly] {
    opacity: 0.7;
    cursor: not-allowed;
    background-color: var(--bs-secondary-bg-subtle);
    /* Adaptable al tema */
  }

  /* Estilo cuando se desbloquea el input */
  .form-control:not([readonly]) {
    cursor: text;
    opacity: 1;
    background-color: var(--bs-body-bg);
  }
</style>
<?php end_block(); ?>

<?php start_block('js'); ?>
<script>
  // 1. Alternar entre Grupo Existente / Nuevo
  function toggleGroupMode() {
    const isNew = document.getElementById('mode_new').checked;
    const sectionExisting = document.getElementById('section_existing');
    const sectionNew = document.getElementById('section_new');

    const selectExisting = document.getElementById('group_id');
    const inputNewName = document.getElementById('new_group_name');

    if (isNew) {
      sectionExisting.classList.add('d-none');
      sectionNew.classList.remove('d-none');

      // Ajustar validación
      selectExisting.removeAttribute('required');
      inputNewName.setAttribute('required', 'required');

      setTimeout(() => inputNewName.focus(), 100);
    } else {
      sectionExisting.classList.remove('d-none');
      sectionNew.classList.add('d-none');

      // Limpiar y ajustar validación
      inputNewName.value = "";
      inputNewName.removeAttribute('required');
      selectExisting.setAttribute('required', 'required');
    }
  }

  // 2. Generar Slug automático (Usando PUNTOS)
  function generateSlug(text, targetId) {
    const slug = text.toString().toLowerCase()
      .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Eliminar acentos
      .replace(/\s+/g, '.')            // Espacios a PUNTOS
      .replace(/[^\w\.]+/g, '')        // Limpiar caracteres raros
      .replace(/\.\.+/g, '.')          // Evitar puntos múltiples
      .replace(/^\.+/, '')             // Trim inicio
      .replace(/\.+$/, '');            // Trim final

    const target = document.getElementById(targetId);
    if (target) target.value = slug;
  }

  // 3. Desbloquear edición de la Key
  function unlockInput(id) {
    const input = document.getElementById(id);
    const btnIcon = document.querySelector(`button[onclick="unlockInput('${id}')"] i`);

    if (input.hasAttribute('readonly')) {
      input.removeAttribute('readonly');
      input.focus();
      if (btnIcon) {
        btnIcon.classList.remove('fa-lock-open');
        btnIcon.classList.add('fa-lock');
      }
    } else {
      input.setAttribute('readonly', 'readonly');
      if (btnIcon) {
        btnIcon.classList.remove('fa-lock');
        btnIcon.classList.add('fa-lock-open');
      }
    }
  }
</script>
<?php end_block(); ?>

<form method="POST" action="" autocomplete="off" class="needs-validation">

  <div class="row g-4">

    <!-- COLUMNA IZQUIERDA: GRUPO -->
    <div class="col-12 col-md-5 col-xl-4">
      <div class="card h-100">
        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <span
              class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle"
              style="width: 32px; height: 32px;">
              <i class="fa-solid fa-layer-group fs-6"></i>
            </span>
            Ubicación del Permiso
          </h5>
        </div>

        <div class="card-body">
          <div class="btn-group w-100 mb-4" role="group">
            <input type="radio" class="btn-check" name="group_mode" id="mode_existing" value="existing" checked
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_existing">Grupo Actual</label>

            <input type="radio" class="btn-check" name="group_mode" id="mode_new" value="new"
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_new">Mover a Nuevo</label>
          </div>

          <!-- MODO: EXISTENTE -->
          <div id="section_existing">
            <label for="group_id" class="form-label fw-medium">Grupo Seleccionado</label>
            <div class="input-group">
              <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-list text-secondary"></i></span>
              <select name="group_id" id="group_id" class="form-select" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($groups as $g): ?>
                  <option value="<?= $g->permission_group_id ?>"
                    <?= $g->permission_group_id == $permission->permission_group_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g->permission_group_name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-text">Cambia el grupo para reclasificar este permiso.</div>
          </div>

          <!-- MODO: NUEVO -->
          <div id="section_new" class="d-none">
            <div class="alert alert-info d-flex align-items-center mb-3 py-2" role="alert">
              <i class="fa-solid fa-arrow-right-arrow-left me-2"></i>
              <small>El permiso se moverá a este nuevo grupo.</small>
            </div>

            <div class="mb-3">
              <label for="new_group_name" class="form-label fw-medium">Nombre Nuevo Grupo</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-tag text-secondary"></i></span>
                <input type="text" class="form-control" name="new_group_name" id="new_group_name"
                  placeholder="Ej: Reportes" onkeyup="generateSlug(this.value, 'new_group_key')">
              </div>
            </div>

            <div class="mb-3">
              <label for="new_group_key" class="form-label fw-medium">Clave (Slug)</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-key text-secondary"></i></span>
                <input type="text" class="form-control" name="new_group_key" id="new_group_key"
                  placeholder="ej: reportes" readonly tabindex="-1">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- COLUMNA DERECHA: DATOS -->
    <div class="col-12 col-md-7 col-xl-8">
      <div class="card h-100">
        <div class="card-header bg-transparent py-3">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <span
              class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning-emphasis rounded-circle"
              style="width: 32px; height: 32px;">
              <i class="fa-solid fa-pen-to-square fs-6"></i>
            </span>
            Editar Datos
          </h5>
        </div>

        <div class="card-body">

          <div class="mb-3">
            <label for="permission_name" class="form-label fw-medium">Nombre del Permiso</label>
            <div class="input-group">
              <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-font text-secondary"></i></span>
              <input type="text" class="form-control" name="permission_name" id="permission_name"
                value="<?= htmlspecialchars($permission->permission_name) ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="permission_key_name" class="form-label fw-medium">Clave del Permiso (Slug)</label>
            <div class="input-group">
              <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-code text-secondary"></i></span>
              <input type="text" class="form-control font-monospace" name="permission_key_name" id="permission_key_name"
                value="<?= htmlspecialchars($permission->permission_key_name) ?>" required readonly>
              <button class="btn btn-outline-secondary" type="button" onclick="unlockInput('permission_key_name')"
                title="Habilitar edición (Peligroso)" data-bs-toggle="tooltip">
                <i class="fa-solid fa-lock-open"></i>
              </button>
            </div>
            <div class="form-text text-warning">
              <i class="fa-solid fa-triangle-exclamation me-1"></i>
              Precaución: Modificar la clave puede romper funcionalidades.
            </div>
          </div>

          <!-- CONTEXTO (Movido aquí) -->
          <div class="mb-3">
            <label class="form-label fw-medium">Contexto <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-globe text-secondary"></i></span>
              <select name="permission_context_id" class="form-select" required>
                <?php foreach ($contexts as $c): ?>
                  <option value="<?= $c->permission_context_id ?>"
                    <?= $c->permission_context_id == $permission->permission_context_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c->permission_context_name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="permission_description" class="form-label fw-medium">Descripción</label>
            <textarea class="form-control" name="permission_description" id="permission_description"
              rows="3"><?= htmlspecialchars($permission->permission_description) ?></textarea>
          </div>

        </div>
      </div>
    </div>

    <!-- BOTONERA -->
    <div class="col-12">
      <div class="card bg-transparent">
        <div class="card-body p-0">
          <div class="d-flex justify-content-end gap-2">
            <a href="<?= admin_route("permissions") ?>" class="btn btn-link text-decoration-none text-muted px-4">
              <i class="fa-solid fa-arrow-left me-2"></i>Volver
            </a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="fa-solid fa-rotate me-2"></i>Actualizar Permiso
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>