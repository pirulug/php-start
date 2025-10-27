<div class="row g-3">
  <?php foreach ($groupedPermissions as $groupName => $perms): ?>
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold text-uppercase"><?= htmlspecialchars($groupName) ?></span>
        <span class="badge bg-primary rounded-pill"><?= count($perms) ?> permisos</span>
      </div>

      <ul class="list-group list-group-flush">
        <?php foreach ($perms as $perm): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex flex-column">
              <span class="fw-semibold"><?= htmlspecialchars($perm->permission_name) ?></span>
              <small class="text-muted">
                ID: <?= $perm->permission_id ?> • Key: <code><?= htmlspecialchars($perm->permission_key_name) ?></code>
              </small>
            </div>

            <div class="btn-group">
              <a href="permission/edit/<?= $perm->permission_id ?>" class="btn btn-outline-success btn-sm"
                data-bs-toggle="tooltip" title="Editar permiso">
                <i class="fa fa-pen"></i>
              </a>

              <button class="btn btn-outline-danger btn-sm" sa-title="¿Eliminar permiso?"
                sa-text="Esta acción no se puede deshacer." sa-icon="warning" sa-confirm-btn-text="Sí, eliminar"
                sa-cancel-btn-text="No, cancelar" sa-redirect-url="permission/delete/<?= $perm->permission_id ?>"
                data-bs-toggle="tooltip" title="Eliminar permiso">
                <i class="fa fa-trash"></i>
              </button>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>
</div>