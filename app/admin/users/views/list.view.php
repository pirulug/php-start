<div class="card">
  <div class="card-body">
    <form method="get" class="mb-3">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Buscar..."
          value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button class="btn btn-primary">Buscar</button>
      </div>
    </form>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Email</th>
            <th>Role</th>
            <th>Estatus</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user->user_name ?></td>
              <td><?= $user->user_email ?></td>
              <td>
                <?= $user->role_id ?>                
              </td>
              <td>
                <?php if ($user->user_status == 1): ?>
                  <span class="badge bg-success">Activo</span>
                <?php else: ?>
                  <span class="badge bg-danger">Desactivo</span>
                <?php endif; ?>
              </td>
              <td>

                <a href="user/edit/<?= $cipher->encrypt($user->user_id) ?>" class="btn btn-sm btn-success"
                  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar">
                  <i class="fa fa-pen"></i>
                </a>

                <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                  data-bs-title="Eliminar" sa-title="¿Eliminar usuario?" sa-text="Esta acción no se puede deshacer."
                  sa-icon="warning" sa-confirm-btn-text="Sí, eliminar" sa-cancel-btn-text="No, cancelar"
                  sa-redirect-url="user/delete/<?= $cipher->encrypt($user->user_id) ?>">
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?= $paginator->renderLinks('?') ?>

  </div>
</div>