<?php block_start("title") ?>
Explorador de Logs
<?php block_end() ?>

<?php block_start("breadcrumb"); ?>
<?php render_breadcrumb([
  ["label" => "Dashboard", "link" => admin_route("dashboard")],
  ["label" => "Explorador de Logs"]
]) ?>
<?php block_end(); ?>

<div class="bg-body p-3 rounded mb-3 d-flex align-items-center justify-content-between flex-column flex-md-row gap-3">
  <div>
    <h5 class="m-0 fw-bold text-uppercase"><i class="fa-solid fa-folder-open me-2 text-warning"></i>Logs del Sistema</h5>
    <span class="badge bg-secondary"><?= count($all_logs) ?> archivos encontrados</span>
  </div>
  <div style="max-width: 300px; width: 100%;">
    <input type="text" id="logSearchInput" class="form-control" placeholder="Buscar por usuario, IP o fecha...">
  </div>
</div>

<!-- TABS DE NAVEGACIÓN -->
<ul class="nav nav-tabs mb-3" id="logsTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-bold text-uppercase" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-pane" type="button" role="tab">
      <i class="fa-solid fa-users me-2"></i>Usuarios
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold text-uppercase" id="ips-tab" data-bs-toggle="tab" data-bs-target="#ips-pane" type="button" role="tab">
      <i class="fa-solid fa-network-wired me-2"></i>Direcciones IP
    </button>
  </li>
  <?php if (!empty($grouped_logs['otros'])): ?>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-bold text-uppercase" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-pane" type="button" role="tab">
        <i class="fa-solid fa-file-invoice me-2"></i>Otros
      </button>
    </li>
  <?php endif; ?>
</ul>

<div class="tab-content" id="logsTabsContent">
  <!-- PANE: USUARIOS -->
  <div class="tab-pane fade show active" id="users-pane" role="tabpanel" tabindex="0">
    <?php if (empty($grouped_logs['usuarios'])): ?>
      <div class="bg-body p-4 rounded text-center text-muted">
        <i class="fa-solid fa-info-circle d-block fs-3 mb-2"></i>
        No se registraron logs de usuarios autenticados todavía.
      </div>
    <?php else: ?>
      <?php 
      $idx_user = 0;
      foreach ($grouped_logs['usuarios'] as $username => $files): 
        $idx_user++;
        $collapse_id = "collapseUser_" . $idx_user;
      ?>
        <div class="card mb-3 search-card" data-search-key="<?= htmlspecialchars(strtolower($username)) ?>">
          <div class="card-header py-3 d-flex align-items-center justify-content-between style-cursor-pointer" 
               data-bs-toggle="collapse" data-bs-target="#<?= $collapse_id ?>" style="cursor: pointer;">
            <h6 class="card-title m-0 fw-bold text-uppercase text-primary">
              <i class="fa-solid fa-user me-2"></i>Usuario: <?= htmlspecialchars($username) ?>
            </h6>
            <span class="badge bg-primary rounded-pill"><?= count($files) ?> logs</span>
          </div>
          <div id="<?= $collapse_id ?>" class="collapse">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle table-sm m-0">
                  <thead>
                    <tr>
                      <th class="ps-3 py-2">Fecha del Log</th>
                      <th class="py-2">Tamaño</th>
                      <th class="py-2">Última Modificación</th>
                      <th class="text-end pe-3 py-2">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($files as $file): ?>
                      <tr class="search-row" data-search-key="<?= htmlspecialchars(strtolower($file['name'])) ?>">
                        <td class="ps-3 py-3 font-monospace fw-bold">
                          <?= htmlspecialchars($file['name']) ?>
                        </td>
                        <td>
                          <?= format_number_decimal($file['size'] / 1024, 2) ?> KB
                        </td>
                        <td class="small text-muted">
                          <?= date('d/m/Y H:i:s', $file['date']) ?>
                        </td>
                        <td class="text-end pe-3">
                          <a href="<?= admin_route("log/view", [], ["f" => $file['relative_path']]) ?>" class="btn btn-outline-primary btn-sm text-uppercase fw-bold">
                            <i class="fa-solid fa-eye me-1"></i> Ver Log
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- PANE: IPS -->
  <div class="tab-pane fade" id="ips-pane" role="tabpanel" tabindex="0">
    <?php if (empty($grouped_logs['ips'])): ?>
      <div class="bg-body p-4 rounded text-center text-muted">
        <i class="fa-solid fa-info-circle d-block fs-3 mb-2"></i>
        No se registraron logs de direcciones IP todavía.
      </div>
    <?php else: ?>
      <?php 
      $idx_ip = 0;
      foreach ($grouped_logs['ips'] as $ip => $files): 
        $idx_ip++;
        $collapse_id = "collapseIp_" . $idx_ip;
        $ip_display = str_replace('_', ':', $ip);
      ?>
        <div class="card mb-3 search-card" data-search-key="<?= htmlspecialchars(strtolower($ip_display)) ?>">
          <div class="card-header py-3 d-flex align-items-center justify-content-between style-cursor-pointer" 
               data-bs-toggle="collapse" data-bs-target="#<?= $collapse_id ?>" style="cursor: pointer;">
            <h6 class="card-title m-0 fw-bold text-uppercase text-secondary">
              <i class="fa-solid fa-location-dot me-2"></i>Dirección IP: <?= htmlspecialchars($ip_display) ?>
            </h6>
            <span class="badge bg-secondary rounded-pill"><?= count($files) ?> logs</span>
          </div>
          <div id="<?= $collapse_id ?>" class="collapse">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle table-sm m-0">
                  <thead>
                    <tr>
                      <th class="ps-3 py-2">Fecha del Log</th>
                      <th class="py-2">Tamaño</th>
                      <th class="py-2">Última Modificación</th>
                      <th class="text-end pe-3 py-2">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($files as $file): ?>
                      <tr class="search-row" data-search-key="<?= htmlspecialchars(strtolower($file['name'])) ?>">
                        <td class="ps-3 py-3 font-monospace fw-bold">
                          <?= htmlspecialchars($file['name']) ?>
                        </td>
                        <td>
                          <?= format_number_decimal($file['size'] / 1024, 2) ?> KB
                        </td>
                        <td class="small text-muted">
                          <?= date('d/m/Y H:i:s', $file['date']) ?>
                        </td>
                        <td class="text-end pe-3">
                          <a href="<?= admin_route("log/view", [], ["f" => $file['relative_path']]) ?>" class="btn btn-outline-primary btn-sm text-uppercase fw-bold">
                            <i class="fa-solid fa-eye me-1"></i> Ver Log
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- PANE: OTROS -->
  <?php if (!empty($grouped_logs['otros'])): ?>
    <div class="tab-pane fade" id="other-pane" role="tabpanel" tabindex="0">
      <div class="bg-body p-3 rounded">
        <div class="table-responsive">
          <table class="table table-hover align-middle table-sm m-0">
            <thead>
              <tr>
                <th class="ps-3 py-2">Archivo</th>
                <th class="py-2">Tamaño</th>
                <th class="py-2">Última Modificación</th>
                <th class="text-end pe-3 py-2">Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($grouped_logs['otros'] as $file): ?>
                <tr class="search-row" data-search-key="<?= htmlspecialchars(strtolower($file['relative_path'])) ?>">
                  <td class="ps-3 py-3 font-monospace fw-bold">
                    <?= htmlspecialchars($file['relative_path']) ?>
                  </td>
                  <td>
                    <?= format_number_decimal($file['size'] / 1024, 2) ?> KB
                  </td>
                  <td class="small text-muted">
                    <?= date('d/m/Y H:i:s', $file['date']) ?>
                  </td>
                  <td class="text-end pe-3">
                    <a href="<?= admin_route("log/view", [], ["f" => $file['relative_path']]) ?>" class="btn btn-outline-primary btn-sm text-uppercase fw-bold">
                      <i class="fa-solid fa-eye me-1"></i> Ver Log
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php block_start("js") ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("logSearchInput");
  
  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      const value = e.target.value.toLowerCase().trim();
      
      // 1. Filtrar las tarjetas de grupos (usuarios o IPs)
      const cards = document.querySelectorAll(".search-card");
      cards.forEach(card => {
        const cardKey = card.getAttribute("data-search-key");
        const rows = card.querySelectorAll(".search-row");
        
        let cardHasMatch = cardKey.includes(value);
        let visibleRowsCount = 0;
        
        // 2. Filtrar filas de logs dentro de cada tarjeta
        rows.forEach(row => {
          const rowKey = row.getAttribute("data-search-key");
          if (rowKey.includes(value) || cardKey.includes(value)) {
            row.style.display = "";
            visibleRowsCount++;
          } else {
            row.style.display = "none";
          }
        });
        
        // Mostrar u ocultar la tarjeta segun si tiene coincidencias
        if (cardHasMatch || visibleRowsCount > 0) {
          card.style.display = "";
          // Si el input de busqueda no esta vacio, expandimos automaticamente
          const collapseEl = card.querySelector(".collapse");
          if (value !== "" && collapseEl && !collapseEl.classList.contains("show")) {
            const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });
            bsCollapse.show();
          }
        } else {
          card.style.display = "none";
        }
      });
      
      // 3. Filtrar tabla de Otros si esta activa
      const otherRows = document.querySelectorAll("#other-pane .search-row");
      otherRows.forEach(row => {
        const rowKey = row.getAttribute("data-search-key");
        if (rowKey.includes(value)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  }
});
</script>
<?php block_end() ?>
