<form method="POST">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Nombre del Rol</label>
            <input type="text" name="role_name" class="form-control" value="<?= $role->role_name ?? "" ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="role_description" id=""><?= $role->role_description ?? "" ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card">
        <div class="card-body">

          <h5>Permisos</h5>

          <?php foreach ($groupedPermissions as $groupName => $perms): ?>
            <fieldset class="border p-3 mb-3 rounded">
              <legend class="w-auto px-2 h6 text-primary"><?= $groupName ?></legend>
              <div class="row g-3">
                <?php foreach ($perms as $perm): ?>
                  <div class="col-md-3">
                    <div class="form-check m-0">
                      <input id="<?= $perm->permission_key_name ?>-pr" class="form-check-input" type="checkbox"
                        name="permissions[]" value="<?= $perm->permission_id ?>" <?= in_array($perm->permission_id, $assigned_permissions) ? 'checked' : '' ?>>
                      <label class="form-check-label" for="<?= $perm->permission_key_name ?>-pr">
                        <?= $perm->permission_name ?>
                        <small class="text-muted d-block">
                          <code><?= $perm->permission_key_name ?></code>
                        </small>
                      </label>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </fieldset>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="d-flex justify-content-between bg-body p-3 rounded">
        <a href="roles.php" class="btn btn-secondary">Volver</a>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </div>
  </div>
</form>