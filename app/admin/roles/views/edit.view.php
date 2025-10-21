<div class="card">
  <div class="card-body">

    <form method="POST">

      <div class="row mb-3">
        <div class="col">
          <label class="form-label">Nombre del Rol</label>
          <input type="text" name="role_name" class="form-control" value="<?= $role->role_name ?? "" ?>" required>
        </div>
        <div class="col">
          <label class="form-label">Descripción</label>
          <input type="text" name="role_description" class="form-control" value="<?= $role->role_description ?? "" ?>">
        </div>
      </div>

      <h5>Permisos</h5>

      <?php foreach ($groupedPermissions as $groupName => $perms): ?>
        <fieldset class="border p-3 mb-4 rounded">
          <legend class="w-auto px-2 h6 text-primary"><?= $groupName ?></legend>
          <div class="row">
            <?php foreach ($perms as $perm): ?>
              <div class="col-md-3 mb-2">
                <div class="form-check">
                  <input id="<?= $perm->permission_key_name ?>-pr" class="form-check-input" type="checkbox"
                    name="permissions[]" value="<?= $perm->permission_id ?>" <?= in_array($perm->permission_id, $assigned_permissions) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="<?= $perm->permission_key_name ?>-pr">
                    <?= $perm->permission_name ?>
                    <small class="text-muted">(<?= $perm->permission_key_name ?>)</small>
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </fieldset>
      <?php endforeach; ?>

      <div class="">
        <a href="roles.php" class="btn btn-secondary">Volver</a>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>