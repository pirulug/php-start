<?php start_block('title'); ?>
Listar Usuarios
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Usuarios', 'link' => admin_route('users')]
]) ?>
<?php end_block(); ?>

<?php start_block('css'); ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?php end_block(); ?>

<div class="card">
  <div class="card-body">

    <div class="mb-3">
      <form method="get" class="d-flex" style="min-width: 280px;">
        <div class="input-group">
          <span class="input-group-text bg-transparent text-secondary">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o email..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          <button class="btn btn-primary px-3">Buscar</button>
        </div>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th scope="col" class=" ps-3">Usuario</th>
            <th scope="col" class="">Rol</th>
            <th scope="col" class="">Estado</th>
            <th scope="col" class=" text-end pe-3">Acciones</th>
          </tr>
        </thead>

        <tbody>

          <?php if (count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
              <tr>
                <td class="ps-3 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                      <?php if (!empty($user->user_image) && file_exists(BASE_DIR . "/storage/uploads/user/" . ($user->user_image))): ?>
                        <img src="<?= APP_URL . "/storage/uploads/user/" . $user->user_image ?>" alt="Avatar"
                          class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
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
                        <?= htmlspecialchars($user->user_login) ?>
                      </span>
                      <span class="text-muted small">
                        <?= htmlspecialchars($user->user_email) ?>
                      </span>
                    </div>
                  </div>
                </td>

                <td>
                  <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2">
                    <i class="fa-solid fa-shield-cat me-1"></i>
                    <?= htmlspecialchars($user->role_name) ?>
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

                  <?= ActionBtn::apiKey(admin_route("users/api", [$cipher->encrypt($user->user_id)]))
                    ->can('users.edit') ?>

                  <?= ActionBtn::edit(admin_route("user/edit", [$cipher->encrypt($user->user_id)]))
                    ->can('users.edit') ?>

                  <?php if ($user->user_status == 1): ?>
                    <?= ActionBtn::deactivate(admin_route("user/deactivate", [$cipher->encrypt($user->user_id)]))
                      ->can('users.deactivate') ?>
                  <?php else: ?>
                    <?= ActionBtn::active(admin_route("user/deactivate", [$cipher->encrypt($user->user_id)]))
                      ->can('users.deactivate') ?>
                  <?php endif; ?>

                  <?= ActionBtn::delete(admin_route("user/delete", [$cipher->encrypt($user->user_id)]))
                    ->can('users.delete')
                    ->saTitle('¿Eliminar a ' . $user->user_login . '?')
                    ->saText('No podrás recuperar sus datos.') ?>

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

    <!-- Paginación Manual Bootstrap 5 -->
    <?php if ($total_pages > 1): ?>
      <nav class="mt-4">
        <ul class="pagination justify-content-center mb-0">
          <?php
          $url_params = $_GET;
          unset($url_params['p']);
          $base_query = http_build_query($url_params);
          $base_url = "?" . ($base_query ? $base_query . "&" : "");

          if ($p > 1): ?>
            <li class="page-item">
              <a class="page-link link-body" href="<?= $base_url ?>p=1" title="Primero">Primero</a>
            </li>
            <li class="page-item">
              <a class="page-link link-body" href="<?= $base_url ?>p=<?= ($p - 1) ?>" aria-label="Anterior">
                <i class="fa-solid fa-chevron-left small"></i>
              </a>
            </li>
          <?php endif; ?>

          <?php
          $range = 2;
          for ($i = 1; $i <= $total_pages; $i++):
            if ($i == 1 || $i == $total_pages || ($i >= $p - $range && $i <= $p + $range)): ?>
              <li class="page-item <?= ($i == $p) ? 'active' : '' ?>">
                <a class="page-link" href="<?= $base_url ?>p=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php elseif ($i == $p - $range - 1 || $i == $p + $range + 1): ?>
              <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif;
          endfor; ?>

          <?php if ($p < $total_pages): ?>
            <li class="page-item">
              <a class="page-link link-body" href="<?= $base_url ?>p=<?= ($p + 1) ?>" aria-label="Siguiente">
                <i class="fa-solid fa-chevron-right small"></i>
              </a>
            </li>
            <li class="page-item">
              <a class="page-link link-body" href="<?= $base_url ?>p=<?= $total_pages ?>" title="Último">Último</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>

  </div>
</div>