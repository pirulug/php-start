<div class="row g-4">
  <?php foreach ($groupedPermissions as $groupName => $perms): ?>
    <div class="col-12 col-md-6 col-xl-4">

      <div class="card h-100">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-layer-group "></i>
            <span
              class="fw-bold text-uppercase small  tracking-wide"><?= htmlspecialchars($groupName) ?></span>
          </div>
          <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
            <?= count($perms) ?>
          </span>
        </div>

        <ul class="list-group list-group-flush">
          <?php foreach ($perms as $perm): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start py-3">

              <div class="me-3">
                <div class="fw-semibold  mb-1">
                  <?= htmlspecialchars($perm->permission_name) ?>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                  <span class="badge bg-secondary fw-normal">
                    <i class="fa-solid fa-key me-1" style="font-size: 0.7em;"></i>
                    <?= htmlspecialchars($perm->permission_key_name) ?>
                  </span>
                  <small class="text-muted" style="font-size: 0.75rem;">#<?= $perm->permission_id ?></small>
                </div>
              </div>

              <div class="d-flex gap-1">
                <a href="permission/edit/<?= $perm->permission_id ?>"
                  class="btn btn-light btn-sm text-primary bg-opacity-10" data-bs-toggle="tooltip" title="Editar">
                  <i class="fa fa-pen fa-fw"></i>
                </a>

                <button class="btn btn-light btn-sm text-danger bg-opacity-10" sa-title="¿Eliminar permiso?"
                  sa-text="Esta acción no se puede deshacer." sa-icon="warning" sa-confirm-btn-text="Sí, eliminar"
                  sa-cancel-btn-text="No, cancelar" sa-redirect-url="permission/delete/<?= $perm->permission_id ?>"
                  data-bs-toggle="tooltip" title="Eliminar">
                  <i class="fa fa-trash fa-fw"></i>
                </button>
              </div>

            </li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>
  <?php endforeach; ?>
</div>