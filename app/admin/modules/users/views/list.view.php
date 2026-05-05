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
<?= url_script_admin('users', 'list') ?>
<?php end_block(); ?>

<!-- Buscador y filtros -->
<div class="bg-body p-3 rounded mb-3 text-end">

  <a href="<?= admin_route('user/new') ?>"
    class="btn btn-primary px-4 d-inline-block mb-3 text-uppercase small fw-bold text-nowrap">
    <i class="fa-solid fa-plus me-2"></i>
    <?= __("Nuevo Usuario") ?>
  </a>

  <form method="get" autocomplete="off">
    <div class="d-flex flex-wrap justify-content-end align-items-center gap-2">

      <!-- Filtro por Rol -->
      <select name="role" class="form-select w-auto" aria-label="Filtrar por rol">
        <option value=""><?= __("Todos los roles") ?></option>
        <?php foreach ($roles as $role): ?>
          <option value="<?= $role->role_id ?>" <?= (isset($_GET['role']) && (string) $_GET['role'] === (string) $role->role_id) ? 'selected' : '' ?>>
            <?= clear_html($role->role_name) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Filtro por Estado -->
      <select name="status" class="form-select w-auto" aria-label="Filtrar por estado">
        <option value=""><?= __("Todos los estados") ?></option>
        <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : '' ?>>Activos</option>
        <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Inactivos</option>
      </select>

      <!-- Buscador -->
      <div class="input-group w-auto flex-grow-1" style="max-width: 450px;">
        <input type="text" id="user_search" name="search" class="form-control"
          placeholder="<?= __("Nombre o email...") ?>" value="<?= clear_html($_GET['search'] ?? '') ?>">
        <button type="submit" class="btn btn-primary px-3 text-uppercase small fw-bold text-nowrap">
          <i class="fa-solid fa-magnifying-glass me-2"></i>
          <?= __("Filtrar") ?>
        </button>
      </div>

      <!-- Botón de limpieza inteligente -->
      <?php if (!empty($_GET['search']) || !empty($_GET['role']) || (isset($_GET['status']) && $_GET['status'] !== '')): ?>
        <a href="<?= admin_route('users') ?>" class="btn btn-outline-secondary px-3"
          title="<?= __('Limpiar todos los filtros') ?>">
          <i class="fa-solid fa-filter-circle-xmark"></i>
        </a>
      <?php endif; ?>

    </div>
  </form>
</div>

<!-- Tabla de Contenido -->
<div class="bg-body p-3 rounded mb-3">

  <div class="table-responsive">
    <table class="table table-hover align-middle table-sm m-0">
      <thead>
        <tr>
          <th class="ps-3"><?= __("Usuario") ?></th>
          <th><?= __("Rol") ?></th>
          <th><?= __("Registro") ?></th>
          <th><?= __("Estado") ?></th>
          <th class="text-end pe-3"><?= __("Acciones") ?></th>
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
                        class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary"
                        style="width: 48px; height: 48px;">
                        <?= strtoupper(substr($user->user_login, 0, 1)) ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="d-flex flex-column">
                    <span class="text-body fw-bold">
                      <?= clear_html($user->user_login) ?>
                    </span>
                    <span class="text-muted small">
                      <?= clear_html($user->user_email) ?>
                    </span>
                  </div>
                </div>
              </td>

              <td>
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">
                  <i class="fa-solid fa-shield-cat me-1"></i>
                  <?= clear_html($role_name ?? $user->role_name) ?>
                </span>
              </td>

              <td>
                <div class="d-flex flex-column">
                  <span class="text-body small">
                    <?= format_date($user->user_created) ?>
                  </span>
                  <span class="text-muted" style="font-size: 0.75rem;">
                    <?= format_time($user->user_created) ?>
                  </span>
                </div>
              </td>

              <td>
                <?php if ($user->user_status == 1): ?>
                  <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                    <span class="text-success small fw-bold text-uppercase"><?= __("Activo") ?></span>
                  </div>
                <?php else: ?>
                  <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-block rounded-circle bg-danger" style="width: 8px; height: 8px;"></span>
                    <span class="text-danger small fw-bold text-uppercase"><?= __("Inactivo") ?></span>
                  </div>
                <?php endif; ?>
              </td>

              <td class="text-end pe-3">
                <div class="d-flex justify-content-end gap-1">
                  <?= ActionBtn::apiKey(admin_route("users/api", [$cipher->encrypt($user->user_id)]))
                    ->can('users.api') ?>

                  <?= ActionBtn::permissions(admin_route("user/permissions", [$cipher->encrypt($user->user_id)]))
                    ->can('users.permissions') ?>

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
                </div>
              </td>
            </tr>
          <?php endforeach; ?>

        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center py-5">
              <div class="d-flex flex-column align-items-center text-muted opacity-50">
                <i class="fa-regular fa-folder-open fa-3x mb-3"></i>
                <h6 class="fw-normal"><?= __("No se encontraron usuarios") ?></h6>
                <?php if (!empty($_GET['search'])): ?>
                  <small><?= __("Intenta con otros términos de búsqueda") ?></small>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endif; ?>

      </tbody>
    </table>
  </div>

</div>

<!-- Paginación -->
<?php if ($total_pages > 1): ?>
  <div class="bg-body p-3 rounded d-flex align-items-center justify-content-between sticky-bottom">
    <div class="legend">
      <span class="fw-bold">
        Mostrando <?= count($users) ?> de <?= $total_rows ?> usuarios
      </span>
    </div>
    <div class="paginator">

      <nav>
        <ul class="pagination justify-content-end mb-0">
          <?php
          $url_params = $_GET;
          unset($url_params['p']);
          $base_query = http_build_query($url_params);
          $base_url   = "?" . ($base_query ? $base_query . "&" : "");

          if ($p > 1): ?>
            <li class="page-item">
              <a class="page-link text-uppercase small fw-bold" href="<?= $base_url ?>p=1">Primero</a>
            </li>
            <li class="page-item">
              <a class="page-link" href="<?= $base_url ?>p=<?= ($p - 1) ?>" aria-label="Anterior">
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
              <a class="page-link" href="<?= $base_url ?>p=<?= ($p + 1) ?>" aria-label="Siguiente">
                <i class="fa-solid fa-chevron-right small"></i>
              </a>
            </li>
            <li class="page-item">
              <a class="page-link text-uppercase small fw-bold" href="<?= $base_url ?>p=<?= $total_pages ?>">Último</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
<?php endif; ?>