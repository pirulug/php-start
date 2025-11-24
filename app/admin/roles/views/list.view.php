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

      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Accion</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($roles as $data): ?>
            <tr>
              <td><?= $data->role_id ?></td>
              <td><?= $data->role_name ?></td>
              <td><?= $data->role_description ?></td>
              <td>
                <a href="rol/edit/<?= $data->role_id ?>" class="btn btn-sm btn-success">
                  <i class="fa fa-pen"></i>
                </a>
                <!-- <button class="btn btn-sm btn-danger" sa-title="¿Eliminar usuario?" sa-text="Esta acción no se puede deshacer."
                  sa-icon="warning" sa-confirm-btn-text="Sí, eliminar" sa-cancel-btn-text="No, cancelar"
                  sa-redirect-url="rol/delete/<?= $data->role_id ?>">
                  <i class="fa fa-trash"></i>
                </button> -->
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?= $dt->renderLinks('?') ?>
    </div>
  </div>
</div>