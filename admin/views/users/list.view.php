<?php require BASE_DIR_ADMIN_VIEW . "/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN_VIEW . "/partials/navbar.partial.php"; ?>

<?php display_messages(); ?>

<div class="card">
  <div class="card-body">
    <form method="GET" action="">
      <div class="input-group">
        <input class="form-control" type="text" name="search" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" type="submit">Buscar</button>
      </div>
    </form>
    <div class="table-responsibe">
      <table class="table">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Email</th>
            <th>Role</th>
            <th>Estarus</th>
            <th>Accion</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user->user_name ?></td>
              <td><?= $user->user_email ?></td>
              <td>
                <?php if ($user->user_role == 1): ?>
                  <span class="badge bg-success">Administrador</span>
                <?php else: ?>
                  <span class="badge bg-info">Suscriptor</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($user->user_status == 1): ?>
                  <span class="badge bg-success">Activo</span>
                <?php else: ?>
                  <span class="badge bg-danger">Desactivo</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="edit.php?id=<?= encrypt($user->user_id) ?>" class="btn btn-success">
                  <i class="fa fa-pen"></i>
                </a>
                <a href="delete.php?id=<?= encrypt($user->user_id) ?>" class="btn btn-danger">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php renderPagination($offset, $limit, $total_results, $page, $search, $total_pages) ?>

  </div>
</div>

<?php require BASE_DIR_ADMIN_VIEW . "/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN_VIEW . "/partials/bottom.partial.php"; ?>