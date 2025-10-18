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
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Key</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($permissions as $data): ?>
            <tr>
              <td><?= $data->permission_id ?></td>
              <td><?= $data->permission_name ?></td>
              <td><?= $data->permission_key_name ?></td>
              <td>
                <a href="permission/edit/<?= $data->permission_id ?>" class="btn btn-sm btn-success">
                  <i class="fa fa-pen"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?= $paginator->renderLinks('?') ?>
  </div>
</div>