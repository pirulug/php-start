<?php start_block('title'); ?>
Listar Roles
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Roles']
]) ?>
<?php end_block(); ?>

<!-- Buscador y filtros -->
<div class="bg-body p-3 rounded mb-3 text-end">
  <a href="<?= admin_route('rol/new') ?>" class="btn btn-primary px-4 d-inline-block mb-3 text-uppercase small fw-bold text-nowrap">
    <i class="fa-solid fa-plus me-2"></i>
    Nuevo Rol
  </a>

  <form method="get" autocomplete="off">
    <div class="d-flex flex-wrap justify-content-end align-items-center gap-2">
      <div class="input-group w-auto flex-grow-1" style="max-width: 450px;">
        <input 
          type="text" 
          name="search" 
          class="form-control" 
          placeholder="Buscar rol por nombre..." 
          value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        >
        <button type="submit" class="btn btn-primary px-3 text-uppercase small fw-bold text-nowrap">
          <i class="fa-solid fa-magnifying-glass me-2"></i>
          Buscar
        </button>
      </div>

      <?php if (!empty($_GET['search'])): ?>
        <a href="<?= admin_route('roles') ?>" class="btn btn-outline-secondary px-3" title="Limpiar filtros">
          <i class="fa-solid fa-filter-circle-xmark"></i>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Tabla de Contenido -->
<div class="bg-body p-3 rounded mb-3">
  <div class="table-responsive">
    <table class="table table-hover align-middle m-0">
      <thead>
        <tr>
          <th class="ps-3" style="width: 80px;">ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th class="text-end pe-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($roles as $data): ?>
          <tr>
            <td class="ps-3">
              <span class="text-muted small fw-bold">#<?= $data->role_id ?></span>
            </td>
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
</div>

<!-- Paginación -->
<?php if ($total_pages > 1): ?>
  <div class="bg-body p-3 rounded d-flex align-items-center justify-content-between sticky-bottom">
    <div class="legend">
      <span class="fw-bold">
        Mostrando <?= count($roles) ?> de <?= $total_rows ?> registros
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