<div class="card">
  <div class="card-body">
    <form method="POST" action="">
      <!-- Grupo existente -->
      <div class="mb-3">
        <label class="form-label">Grupo Existente</label>
        <select name="group_id" id="group_id" class="form-select">
          <option value="">-- Selecciona un grupo --</option>
          <?php foreach ($groups as $g): ?>
            <option value="<?= $g->permission_group_id ?>" <?= $g->permission_group_id == $permission->permission_group_id ? 'selected' : '' ?>>
              <?= $g->permission_group_name ?>
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

      <hr>

      <!-- Datos del permiso -->
      <div class="mb-3">
        <label for="permission_name" class="form-label">Nombre del Permiso</label>
        <input type="text" class="form-control" name="permission_name" id="permission_name"
          value="<?= htmlspecialchars($permission->permission_name) ?>" required>
      </div>

      <div class="mb-3">
        <label for="permission_key_name" class="form-label">Clave del Permiso</label>
        <input type="text" class="form-control" name="permission_key_name" id="permission_key_name"
          value="<?= htmlspecialchars($permission->permission_key_name) ?>" required>
      </div>

      <div class="mb-3">
        <label for="permission_description" class="form-label">Descripción</label>
        <textarea class="form-control" name="permission_description" id="permission_description"
          rows="2"><?= htmlspecialchars($permission->permission_description) ?></textarea>
      </div>

      <div class="">
        <a href="<?= url_admin("permissions") ?>" class="btn btn-secondary">
          <i class="fa fa-arrow-left"></i>
          Volver
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-save"></i>
          Actualizar
        </button>
      </div>
    </form>
  </div>
</div>