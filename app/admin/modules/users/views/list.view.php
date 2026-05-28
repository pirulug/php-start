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
<script>
  const APP_ADMIN_URL = "<?= admin_route() ?>";
</script>
<?= admin_modules_script('users', 'list') ?>
<?php end_block(); ?>

<!-- Buscador y filtros -->
<div class="bg-body p-3 rounded mb-3 text-end">
  <?= Button::new(admin_route('user/new'))->text('Nuevo Usuario') ?>

  <hr class="my-2">

  <?= Filter::make(admin_route('users'))
    ->select('role', __('Todos los roles'), $roles, 'role_id', 'role_name')
    ->select('status', __('Todos los estados'), [1 => __('Activos'), 0 => __('Inactivos')])
    ->search(__('Nombre o email...'))
    ->render() ?>
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
                  <?= Table::avatar(storage_uploads($user->user_image, "user"), $user->user_login) ?>
                  <div class="d-flex flex-column">
                    <?= Table::text($user->user_login)->bold() ?>
                    <?= Table::text($user->user_email)->muted()->small() ?>
                  </div>
                </div>
              </td>

              <td>
                <?= Table::badge($user->role_name)->icon('fa-solid fa-shield-cat') ?>
              </td>

              <td>
                <?= Table::date($user->user_created) ?>
              </td>

              <td>
                <?= Table::status($user->user_status) ?>
              </td>

              <td class="text-end pe-3">
                <div class="d-flex justify-content-end gap-1">
                  <?= Button::link(admin_route("user/api", [$cipher->encrypt($user->user_id)], ["p" => $p]))
                    ->icon('fa-solid fa-key')
                    ->text('API')
                    ->can('users.api') ?>

                  <?= Button::link(admin_route("user/permissions", [$cipher->encrypt($user->user_id)], ["p" => $p]))
                    ->icon('fa-solid fa-user-shield')
                    ->classes('btn btn-sm btn-secondary')
                    ->text('Permisos')
                    ->can('users.permissions') ?>

                  <?= Button::edit(admin_route("user/edit", [$cipher->encrypt($user->user_id)], ["p" => $p]))
                    ->can('users.edit') ?>

                  <?php if ($user->user_status == 1): ?>
                    <?= Button::deactivate(admin_route("user/deactivate", [$cipher->encrypt($user->user_id)], ["p" => $p]))
                      ->can('users.deactivate') ?>
                  <?php else: ?>
                    <?= Button::active(admin_route("user/deactivate", [$cipher->encrypt($user->user_id)], ["p" => $p]))
                      ->can('users.deactivate') ?>
                  <?php endif; ?>     

                  <?= Button::delete(admin_route("user/delete", [$cipher->encrypt($user->user_id)], ["p" => $p]))
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
<?php $paginator = Pager::make($total_rows, $limit)->items('usuarios'); ?>

<?php if ($total_rows > $limit): ?>
  <div class="bg-body p-3 rounded d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 sticky-bottom">
    <?= $paginator->legend() ?>
    <?= $paginator->render() ?>
  </div>
<?php endif; ?>