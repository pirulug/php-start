<?php start_block('title'); ?>
Gestión de Políticas
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Políticas']
]) ?>
<?php end_block(); ?>

<!-- Buscador y filtros -->
<div class="bg-body p-3 rounded mb-3 text-end">
  <a href="<?= admin_route('policy/new') ?>" class="btn btn-primary px-4 d-inline-block mb-3 text-uppercase small fw-bold text-nowrap">
    <i class="fa-solid fa-plus me-2"></i>
    Nueva Política
  </a>

  <form method="get" autocomplete="off">
    <div class="d-flex flex-wrap justify-content-end align-items-center gap-2">
      
      <!-- Filtro por Estado -->
      <select name="status" class="form-select w-auto" aria-label="Filtrar por estado">
        <option value="">Todos los estados</option>
        <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : '' ?>>Activos</option>
        <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Borradores</option>
      </select>

      <div class="input-group w-auto flex-grow-1" style="max-width: 450px;">
        <input 
          type="text" 
          name="search" 
          class="form-control" 
          placeholder="Buscar política..." 
          value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        >
        <button type="submit" class="btn btn-primary px-3 text-uppercase small fw-bold text-nowrap">
          <i class="fa-solid fa-magnifying-glass me-2"></i>
          Buscar
        </button>
      </div>

      <?php if (!empty($_GET['search']) || (isset($_GET['status']) && $_GET['status'] !== '')): ?>
        <a href="<?= admin_route('policies') ?>" class="btn btn-outline-secondary px-3" title="Limpiar búsqueda">
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
          <th class="ps-3">Documento</th>
          <th>Ruta</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th class="text-end pe-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($policies as $p): ?>
          <tr>
            <td class="ps-3">
              <div class="fw-bold text-body"><?= htmlspecialchars($p->post_title) ?></div>
              <?php if (in_array($p->post_slug, $protected_slugs)): ?>
                <span class="badge bg-info-subtle text-info small border border-info-subtle text-uppercase" style="font-size: 0.65rem;">Sistema</span>
              <?php endif; ?>
            </td>
            <td>
              <code class="text-primary small">/<?= $p->post_slug ?></code>
            </td>
            <td>
              <?php if ($p->policy_type === 'faq'): ?>
                <span class="text-purple small fw-bold text-uppercase">
                  <i class="fa-solid fa-circle-question me-1"></i> FAQ
                </span>
              <?php else: ?>
                <span class="text-blue small fw-bold text-uppercase">
                  <i class="fa-solid fa-file-lines me-1"></i> Legal
                </span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($p->post_status): ?>
                <div class="d-flex align-items-center gap-1">
                  <span class="bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                  <span class="small fw-bold text-uppercase">Activo</span>
                </div>
              <?php else: ?>
                <div class="d-flex align-items-center gap-1">
                  <span class="bg-secondary rounded-circle" style="width: 8px; height: 8px;"></span>
                  <span class="small fw-bold text-uppercase">Borrador</span>
                </div>
              <?php endif; ?>
            </td>
            <td class="text-end pe-3">
              <div class="d-flex justify-content-end gap-2">
                <?php if ($p->post_status == 1): ?>
                  <?= ActionBtn::deactivate(admin_route("policy/status", [$cipher->encrypt($p->post_id)]))
                    ->can('policies.status') ?>
                <?php else: ?>
                  <?= ActionBtn::active(admin_route("policy/status", [$cipher->encrypt($p->post_id)]))
                    ->can('policies.status') ?>
                <?php endif; ?>

                <?= ActionBtn::edit(admin_route('policy/edit', [$cipher->encrypt($p->post_id)]))
                  ->can('policies.edit') ?>

                <?php if (!in_array($p->post_slug, $protected_slugs)): ?>
                  <?= ActionBtn::delete(admin_route('policy/delete', [$cipher->encrypt($p->post_id)]))
                    ->can('policies.delete') ?>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($policies)): ?>
          <tr>
            <td colspan="5" class="text-center py-5">
              <div class="d-flex flex-column align-items-center text-muted opacity-50">
                <i class="fa-regular fa-file-excel fa-3x mb-3"></i>
                <h6 class="fw-normal">No se encontraron políticas</h6>
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
        Mostrando <?= count($policies) ?> de <?= $total_rows ?> registros
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