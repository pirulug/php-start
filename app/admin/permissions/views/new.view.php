<form method="POST" action="">
  <div class="card mb-3">
    <div class="card-body">

      <!-- Grupo existente -->
      <div class="mb-3">
        <label class="form-label">Seleccionar Grupo Existente</label>
        <select name="group_id" id="group_id" class="form-select">
          <option value="">-- Selecciona un grupo existente --</option>
          <?php foreach ($groups as $g): ?>
            <option value="<?= htmlspecialchars($g->permission_group_id) ?>">
              <?= htmlspecialchars($g->permission_group_name) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>



      <div class="text-center my-3">
        <span class="badge bg-secondary">O crear nuevo grupo</span>
      </div>


      <!-- Crear nuevo grupo -->
      <div class="mb-3">
        <label for="new_group_name" class="form-label">Nombre del nuevo grupo</label>
        <input type="text" class="form-control" name="new_group_name" id="new_group_name"
          placeholder="Ej: Configuración">
      </div>

      <div class="mb-3">
        <label for="new_group_key" class="form-label">Clave del nuevo grupo</label>
        <input type="text" class="form-control" name="new_group_key" id="new_group_key" placeholder="Ej: settings">
      </div>

    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">

      <!-- Datos del permiso -->
      <div class="mb-3">
        <label for="permission_name" class="form-label">Nombre del Permiso</label>
        <input type="text" class="form-control" name="permission_name" id="permission_name" required>
      </div>

      <div class="mb-3">
        <label for="permission_key_name" class="form-label">Clave del Permiso</label>
        <input type="text" class="form-control" name="permission_key_name" id="permission_key_name"
          placeholder="Ej: users.edit" required>
      </div>

      <div class="mb-3">
        <label for="permission_description" class="form-label">Descripción (opcional)</label>
        <textarea class="form-control" name="permission_description" id="permission_description" rows="2"></textarea>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between bg-body p-3 rounded">
    <a href="<?= url_admin("permissions") ?>" class="btn btn-secondary">
      Volver
    </a>
    <button type="submit" class="btn btn-primary">
      Guardar
    </button>
  </div>

</form>