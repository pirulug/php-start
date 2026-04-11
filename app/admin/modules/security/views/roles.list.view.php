<?php start_block('title'); ?>
Listar Roles
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Roles']
]) ?>
<?php end_block(); ?>

<?php start_block('css'); ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?php end_block(); ?>

<div class="card">
  <div class="card-body">

    <div class="mb-3">
      <form method="get">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Buscar rol..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          <button class="btn btn-primary text-uppercase small fw-bold">
            <i class="fa-solid fa-search"></i>
            Buscar
          </button>
        </div>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle m-0">
        <thead>
          <tr>
            <th scope="col" class="" style="width: 80px;">ID</th>
            <th scope="col" class="">Nombre</th>
            <th scope="col" class="">Descripción</th>
            <th scope="col" class="text-end ">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($roles as $data): ?>
            <tr>
              <td>
                <span class="text-muted">#<?= $data->role_id ?></span>
              </td>

              <td>
                <span class="fw-bold text-body-emphasis"><?= $data->role_name ?></span>
              </td>

              <td>
                <div class="text-secondary text-truncate" style="max-width: 300px;"
                  title="<?= htmlspecialchars($data->role_description) ?>">
                  <?= $data->role_description ?>
                </div>
              </td>

              <td class="text-end">
                <div class="">
                  <!-- <a href="rol/edit/<?= $data->role_id ?>" class="btn btn-sm btn-success">
                    <i class="fa fa-pen"></i>
                    Editar
                  </a>

                  <button class="btn btn-sm btn-danger" sa-title="¿Eliminar rol?"
                    sa-text="Esta acción no se puede deshacer." sa-icon="warning" sa-confirm-btn-text="Sí, eliminar"
                    sa-cancel-btn-text="No, cancelar" sa-redirect-url="rol/delete/<?= $data->role_id ?>">
                    <i class="fa fa-trash"></i>
                    Eliminar
                  </button> -->

                  <?= ActionBtn::edit(admin_route("rol/edit", [$cipher->encrypt($data->role_id)]))
                    ->can('roles.edit') ?>

                  <?= ActionBtn::delete(admin_route("rol/delete", [$cipher->encrypt($data->role_id)]))
                    ->can('roles.delete')
                    ->saTitle('¿Eliminar a ' . $data->role_name . '?')
                    ->saText('No podrás recuperar sus datos.') ?>

                </div>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($roles)): ?>
            <tr>
              <td colspan="4" class="text-center py-5 text-muted">
                <i class="fa-regular fa-folder-open fa-2x mb-3 d-block"></i>
                No se encontraron resultados para "<?= htmlspecialchars($_GET['search'] ?? '') ?>"
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($total_pages > 1): ?>
      <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-secondary">
          Mostrando <?= ($offset + 1) ?> a <?= min($offset + $limit, $total_rows) ?> de <?= $total_rows ?> registros
        </small>
        
        <nav aria-label="Page navigation">
          <ul class="pagination pagination-sm mb-0">
            <?php if ($p > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?p=<?= $p - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Anterior</a>
              </li>
            <?php else: ?>
              <li class="page-item disabled"><span class="page-link">Anterior</span></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <?php if ($i == 1 || $i == $total_pages || ($i >= $p - 2 && $i <= $p + 2)): ?>
                <li class="page-item <?= $i === $p ? 'active' : '' ?>">
                  <a class="page-link" href="?p=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                </li>
              <?php elseif ($i == $p - 3 || $i == $p + 3): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
              <?php endif; ?>
            <?php endfor; ?>

            <?php if ($p < $total_pages): ?>
              <li class="page-item">
                <a class="page-link" href="?p=<?= $p + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Siguiente</a>
              </li>
            <?php else: ?>
              <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    <?php endif; ?>

  </div>
</div>