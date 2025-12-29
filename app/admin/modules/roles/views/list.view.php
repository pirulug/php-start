<?php start_block('title'); ?>
Listar Roles
<?php end_block(); ?>

<?php start_block('css'); ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?php end_block(); ?>

<div class="card">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="card-title mb-0 fw-bold">
        <i class="fa-solid fa-users-gear text-primary me-2"></i>Gestión de Roles
      </h5>

      <form method="get" class="d-flex" style="max-width: 300px;">
        <div class="input-group">
          <span class="input-group-text bg-transparent">
            <i class="fa-solid fa-magnifying-glass text-secondary"></i>
          </span>
          <input type="text" name="search" class="form-control" placeholder="Buscar rol..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
          <button class="btn btn-primary">
            Buscar
          </button>
        </div>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th scope="col" class="text-secondary text-uppercase small" style="width: 80px;">ID</th>
            <th scope="col" class="text-secondary text-uppercase small">Nombre</th>
            <th scope="col" class="text-secondary text-uppercase small">Descripción</th>
            <th scope="col" class="text-end text-secondary text-uppercase small" style="width: 150px;">Acciones</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
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
                <div class="btn-group btn-group-sm">
                  <a href="rol/edit/<?= $data->role_id ?>" class="btn btn-outline-primary" data-bs-toggle="tooltip"
                    title="Editar">
                    <i class="fa fa-pen"></i>
                  </a>

                  <button class="btn btn-outline-danger" sa-title="¿Eliminar rol?"
                    sa-text="Esta acción no se puede deshacer." sa-icon="warning" sa-confirm-btn-text="Sí, eliminar"
                    sa-cancel-btn-text="No, cancelar" sa-redirect-url="rol/delete/<?= $data->role_id ?>"
                    data-bs-toggle="tooltip" title="Eliminar">
                    <i class="fa fa-trash"></i>
                  </button>
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

    <div class="d-flex justify-content-end mt-3">
      <?= $dt->renderLinks('?') ?>
    </div>

  </div>
</div>