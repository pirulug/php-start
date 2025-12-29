<?php start_block('title'); ?>
Listar Usuarios
<?php end_block(); ?>

<?php start_block('css'); ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?php end_block(); ?>

<div class="card">
  <div class="card-body">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

      <div>
        <h5 class="card-title mb-1 d-flex align-items-center gap-2">
          <i class="fa-solid fa-users text-primary"></i>
          Directorio de Usuarios
        </h5>
        <div class="text-muted small">Gestión de accesos y perfiles</div>
      </div>

      <form method="get" class="d-flex" style="min-width: 280px;">
        <div class="input-group">
          <span class="input-group-text bg-transparent text-secondary">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o email..."
            value="<?= ($_GET['search'] ?? '') ?>">
          <button class="btn btn-primary px-3">Buscar</button>
        </div>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th scope="col" class="text-muted small text-uppercase fw-bold ps-3">Usuario</th>
            <th scope="col" class="text-muted small text-uppercase fw-bold">Rol</th>
            <th scope="col" class="text-muted small text-uppercase fw-bold">Estado</th>
            <th scope="col" class="text-muted small text-uppercase fw-bold text-end pe-3">Acciones</th>
          </tr>
        </thead>

        <tbody class="table-group-divider">

          <?php if (count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
              <tr>
                <td class="ps-3 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                      <?php if (!empty($user->user_image) && file_exists(APP_URL . "/uploads/user/" . $user->user_image)): ?>
                        <img src="<?= APP_URL . "/uploads/user/" . $user->user_image ?>" alt="Avatar" class="rounded-circle"
                          style="width: 48px; height: 48px; object-fit: cover;">
                      <?php else: ?>
                        <div
                          class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary fw-bold"
                          style="width: 48px; height: 48px;">
                          <?= strtoupper(substr($user->user_login, 0, 1)) ?>
                        </div>
                      <?php endif; ?>
                    </div>

                    <div class="d-flex flex-column">
                      <span class="fw-bold text-body">
                        <?= ($user->user_login) ?>
                      </span>
                      <span class="text-muted small">
                        <?= ($user->user_email) ?>
                      </span>
                    </div>
                  </div>
                </td>

                <td>
                  <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2">
                    <i class="fa-solid fa-shield-cat me-1"></i>
                    <?= ($user->role_name) ?>
                  </span>
                </td>

                <td>
                  <?php if ($user->user_status == 1): ?>
                    <div class="d-flex align-items-center gap-2">
                      <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                      <span class="text-success fw-medium">Activo</span>
                    </div>
                  <?php else: ?>
                    <div class="d-flex align-items-center gap-2">
                      <span class="d-inline-block rounded-circle bg-danger" style="width: 8px; height: 8px;"></span>
                      <span class="text-danger fw-medium">Inactivo</span>
                    </div>
                  <?php endif; ?>
                </td>

                <td class="text-end pe-3">
                  <div class="btn-group">
                    <a href="user/edit/<?= $cipher->encrypt($user->user_id) ?>" class="btn btn-sm btn-outline-secondary"
                      data-bs-toggle="tooltip" title="Editar cuenta">
                      <i class="fa-solid fa-pen"></i>
                    </a>

                    <button class="btn btn-sm btn-outline-danger" sa-title="¿Eliminar a <?= ($user->user_login) ?>?"
                      sa-text="Esta acción eliminará el acceso del usuario permanentemente." sa-icon="warning"
                      sa-confirm-btn-text="Sí, eliminar" sa-cancel-btn-text="Cancelar"
                      sa-redirect-url="user/delete/<?= $cipher->encrypt($user->user_id) ?>" data-bs-toggle="tooltip"
                      title="Eliminar usuario">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>

          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center py-5">
                <div class="d-flex flex-column align-items-center text-muted opacity-50">
                  <i class="fa-regular fa-folder-open fa-3x mb-3"></i>
                  <h6 class="fw-normal">No se encontraron usuarios</h6>
                  <?php if (!empty($_GET['search'])): ?>
                    <small>Intenta con otros términos de búsqueda</small>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endif; ?>

        </tbody>
      </table>
    </div>

    <?php if (count($users) > 0): ?>
      <div class="d-flex justify-content-end mt-4 pt-3 border-top border-light">
        <?= $dt->renderLinks('?') ?>
      </div>
    <?php endif; ?>

  </div>
</div>