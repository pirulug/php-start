<?php $theme->blockStart("style") ?>
<link rel="stylesheet" href="<?= SITE_URL_ADMIN ?>/assets/css/sweetalert2.css">
<?php $theme->blockEnd("style") ?>

<?php $theme->blockStart("script") ?>
<script src="<?= SITE_URL_ADMIN ?>/assets/js/sweetalert2.js"></script>
<script src="<?= SITE_URL_ADMIN ?>/assets/js/sw-dinamico.js"></script>
<?php $messageHandler->displaySweetAlerts(); ?>
<?php $theme->blockEnd("script") ?>

<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>

<div class="card">
  <div class="card-body">
    <form method="GET" action="">
      <div class="input-group mb-3">
        <input class="form-control" type="text" name="search" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" type="submit">Buscar</button>
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
                <?php if ($user->user_role == 2): ?>
                  <span class="badge bg-success">Administrador</span>
                <?php elseif ($user->user_role == 3): ?>
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

                <a href="edit.php?id=<?= $encryption->encrypt($user->user_id) ?>" class="btn btn-sm btn-success"
                  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar">
                  <i class="fa fa-pen"></i>
                </a>

                <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                  data-bs-title="Eliminar" sw-title="¿Eliminar usuario?" sw-text="Esta acción no se puede deshacer."
                  sw-icon="warning" sw-confirm-btn-text="Sí, eliminar" sw-cancel-btn-text="No, cancelar"
                  sw-redirect-url="delete.php?id=<?= $encryption->encrypt($user->user_id) ?>">
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php renderPagination($offset, $limit, $total_results, $page, $search, $total_pages) ?>

  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/bottom.partial.php"; ?>