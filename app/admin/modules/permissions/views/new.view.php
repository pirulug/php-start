<?php start_block('title'); ?>
Nuevo Permiso
<?php end_block(); ?>

<?php start_block('css'); ?>
<style>
  /* Ajustes sutiles para inputs de solo lectura en dark mode */
  .form-control[readonly] {
    opacity: 0.7;
    cursor: not-allowed;
  }
</style>
<?php end_block(); ?>

<?php start_block('js'); ?>
<script>
  // 1. Lógica para mostrar/ocultar secciones (Tabs)
  function toggleGroupMode() {
    const isNew = document.getElementById('mode_new').checked;
    const sectionExisting = document.getElementById('section_existing');
    const sectionNew = document.getElementById('section_new');

    // Inputs a resetear/deshabilitar
    const selectExisting = document.getElementById('group_id');
    const inputNewName = document.getElementById('new_group_name');
    const inputNewKey = document.getElementById('new_group_key');

    if (isNew) {
      sectionExisting.classList.add('d-none');
      sectionNew.classList.remove('d-none');

      // Lógica de formulario
      selectExisting.value = "";
      selectExisting.removeAttribute('required');

      inputNewName.setAttribute('required', 'required');
      inputNewKey.setAttribute('required', 'required');

      // Auto-focus para UX
      setTimeout(() => inputNewName.focus(), 100);

    } else {
      sectionExisting.classList.remove('d-none');
      sectionNew.classList.add('d-none');

      // Lógica de formulario
      inputNewName.value = "";
      inputNewKey.value = "";
      inputNewName.removeAttribute('required');
      inputNewKey.removeAttribute('required');

      selectExisting.setAttribute('required', 'required');
    }
  }

  // 2. Lógica para Auto-Slug (Nombre -> Clave con PUNTOS)
  function generateSlug(text, targetId) {
    const slug = text.toString().toLowerCase()
      .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Eliminar acentos
      .replace(/\s+/g, '.')            // Espacios a PUNTOS
      .replace(/[^\w\.]+/g, '')        // Eliminar caracteres no permitidos (deja letras, números, _ y puntos)
      .replace(/\.\.+/g, '.')          // Reemplazar múltiples puntos
      .replace(/^\.+/, '')             // Trim inicio
      .replace(/\.+$/, '');            // Trim final

    const target = document.getElementById(targetId);
    if (target) {
      target.value = slug;
    }
  }

  // 3. Desbloquear input de slug si el usuario quiere editarlo
  function unlockInput(id) {
    const input = document.getElementById(id);
    input.removeAttribute('readonly');
    input.focus();
    input.select();
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
            Asignación
          </h5>
        </div>

        <div class="card-body">
          <p class="small text-muted mb-3">Define a qué grupo pertenecerá este nuevo permiso.</p>

          <div class="btn-group w-100 mb-4" role="group" aria-label="Modo de grupo">
            <input type="radio" class="btn-check" name="group_mode" id="mode_existing" value="existing" checked
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_existing">Existente</label>

            <input type="radio" class="btn-check" name="group_mode" id="mode_new" value="new"
              onchange="toggleGroupMode()">
            <label class="btn btn-outline-secondary" for="mode_new">Crear Nuevo</label>
          </div>

          <!-- MODO: EXISTENTE -->
          <div id="section_existing">
            <label for="group_id" class="form-label fw-medium">Seleccionar Grupo</label>
            <div class="input-group">
              <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-list text-secondary"></i></span>
              <select name="group_id" id="group_id" class="form-select" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($groups as $g): ?>
                  <option value="<?= htmlspecialchars($g->permission_group_id) ?>">
                    <?= htmlspecialchars($g->permission_group_name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-text">El permiso se anidará bajo este grupo.</div>
          </div>

          <!-- MODO: NUEVO -->
          <div id="section_new" class="d-none">
            <div class="alert alert-info d-flex align-items-center mb-3 py-2" role="alert">
              <i class="fa-solid fa-circle-info me-2"></i>
              <small>Creando nueva categoría raíz.</small>
            </div>

            <div class="mb-3">
              <label for="new_group_name" class="form-label fw-medium">Nombre del Grupo</label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-tag text-secondary"></i></span>
                <input type="text" class="form-control" name="new_group_name" id="new_group_name"
                  placeholder="Ej: Reportes Financieros" onkeyup="generateSlug(this.value, 'new_group_key')">
              </div>
            </div>

            <div class="mb-3">
              <label for="new_group_key" class="form-label fw-medium">Clave (Slug)</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary-subtle"><i class="fa-solid fa-key text-muted"></i></span>
                <input type="text" class="form-control bg-secondary-subtle" name="new_group_key" id="new_group_key"
                  placeholder="ej: reportes.financieros" readonly tabindex="-1">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- COLUMNA DERECHA: DETALLES -->
    <div class="col-12 col-md-7 col-xl-8">
      <div class="card h-100">
        <div class="card-header bg-transparent py-3">
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
              <label for="permission_name" class="form-label fw-medium">Nombre del Permiso <span
                  class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-font text-secondary"></i></span>
                <input type="text" class="form-control" name="permission_name" id="permission_name"
                  placeholder="Ej: Exportar PDF" required onkeyup="generateSlug(this.value, 'permission_key_name')">
              </div>
            </div>

            <!-- Clave / Slug -->
            <div class="col-12">
              <label for="permission_key_name" class="form-label fw-medium">Clave del Sistema <span
                  class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-secondary-subtle"><i class="fa-solid fa-code text-muted"></i></span>
                <input type="text" class="form-control font-monospace bg-secondary-subtle" name="permission_key_name"
                  id="permission_key_name" placeholder="Ej: exportar.pdf" required readonly>
                <button class="btn btn-outline-secondary" type="button" onclick="unlockInput('permission_key_name')"
                  title="Editar manualmente">
                  <i class="fa-solid fa-pen"></i>
                </button>
              </div>
              <div class="form-text">Identificador único utilizado por el backend y la API.</div>
            </div>

            <!-- Contexto (Movido aquí para mejor flujo) -->
            <div class="col-12 col-lg-6">
              <label class="form-label fw-medium">Contexto de Aplicación <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary">
                  <i class="fa-solid fa-globe text-secondary"></i>
                </span>
                <select name="permission_context_id" class="form-select" required>
                  <option value="">-- Selecciona --</option>
                  <?php foreach ($contexts as $c): ?>
                    <option value="<?= $c->permission_context_id ?>">
                      <?= htmlspecialchars($c->permission_context_name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Descripción -->
            <div class="col-12">
              <label for="permission_description" class="form-label fw-medium">Descripción Opcional</label>
              <textarea class="form-control" name="permission_description" id="permission_description" rows="3"
                placeholder="Detalles técnicos o funcionales sobre lo que habilita este permiso..."></textarea>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- BOTONERA -->
    <div class="col-12">
      <div class="card bg-transparent"> <!-- Contenedor simple para alinear -->
        <div class="card-body p-0">
          <div class="d-flex justify-content-end gap-2">
            <a href="<?= admin_route("permissions") ?>" class="btn btn-link text-decoration-none text-muted px-4">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Permiso
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>