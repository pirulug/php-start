<?php start_block("title"); ?>
Listar Roles
<?php end_block(); ?>

<?php start_block("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Roles"]
]) ?>
<?php end_block(); ?>

<!-- Buscador y filtros -->
<div class="bg-body p-3 rounded mb-3 text-end">
  <?= Button::new(admin_route("rol/new"))->text("Nuevo Rol") ?>

  <hr class="my-2">

  <?= 
    Filter::make(admin_route("roles"))
      ->search(__("Nombre o descripción..."))
      ->render()
  ?>
</div>

<!-- Tabla de Contenido -->
<div class="bg-body p-3 rounded mb-3">
  <div class="table-responsive">
    <table class="table table-hover align-middle m-0">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Descripción</th>
          <th class="text-end pe-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($roles as $data): ?>
          <tr>
            <td>
              <span class="fw-bold text-body"><?= htmlspecialchars($data->role_name) ?></span>
            </td>
            <td>
              <div class="text-muted small text-truncate" style="max-width: 300px;"
                title="<?= htmlspecialchars($data->role_description) ?>">
                <?= htmlspecialchars($data->role_description) ?>
              </div>
            </td>
            <td class="text-end pe-3">
              <div class="d-flex justify-content-end gap-2">
                <?= Button::edit(admin_route("rol/edit", [$cipher->encrypt($data->role_id)]))
                  ->can("roles.edit") ?>

                <?= Button::delete(admin_route("rol/delete", [$cipher->encrypt($data->role_id)]))
                  ->can("roles.delete")
                  ->saTitle("¿Eliminar a " . $data->role_name . "?")
                  ->saText("No podrás recuperar sus datos.") ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($roles)): ?>
          <tr>
            <td colspan="4" class="text-center py-5">
              <div class="d-flex flex-column align-items-center text-muted opacity-50">
                <i class="fa-regular fa-folder-open fa-3x mb-3"></i>
                <h6 class="fw-normal">No se encontraron roles</h6>
                <?php if (!empty($_GET["search"])): ?>
                  <small>Intenta con otros términos de búsqueda</small>
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
<?php $paginator = Pager::make($total_rows, $limit)->items("roles"); ?>

<?php if ($total_rows > $limit): ?>
  <div class="bg-body p-3 rounded d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 sticky-bottom">
    <?= $paginator->legend() ?>
    <?= $paginator->render() ?>
  </div>
<?php endif; ?>