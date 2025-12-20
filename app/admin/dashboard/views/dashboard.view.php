<div class="row g-4 mb-4">

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 bg-primary bg-opacity-10">
      <div class="card-body d-flex align-items-center">
        <div class="p-3 rounded-circle bg-primary text-white me-3">
          <i class="fa-solid fa-users fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted text-uppercase fw-bold small mb-1">Usuarios</h6>
          <h2 class="mb-0 fw-bold text-primary"><?= number_format($count_user) ?></h2>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 bg-success bg-opacity-10">
      <div class="card-body d-flex align-items-center">
        <div class="p-3 rounded-circle bg-success text-white me-3">
          <i class="fa-solid fa-wifi fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted text-uppercase fw-bold small mb-1">En Línea</h6>
          <h2 class="mb-0 fw-bold text-success"><?= number_format($count_online) ?></h2>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 bg-info bg-opacity-10">
      <div class="card-body d-flex align-items-center">
        <div class="p-3 rounded-circle bg-info text-white me-3">
          <i class="fa-solid fa-eye fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted text-uppercase fw-bold small mb-1">Páginas Vistas</h6>
          <h2 class="mb-0 fw-bold text-info"><?= number_format($total_views) ?></h2>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card h-100 bg-warning bg-opacity-10">
      <div class="card-body d-flex align-items-center">
        <div class="p-3 rounded-circle bg-warning text-dark me-3">
          <i class="fa-solid fa-earth-americas fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted text-uppercase fw-bold small mb-1">Visitantes</h6>
          <h2 class="mb-0 fw-bold text-warning"><?= number_format($total_visitors) ?></h2>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">

  <div class="col-12 col-lg-8">
    <div class="card h-100">
      <div class="card-header bg-transparent py-3">
        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
          <i class="fa-solid fa-arrow-trend-up text-primary"></i>
          Páginas más visitadas
        </h5>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-secondary bg-opacity-10">
            <tr>
              <th class="text-uppercase small fw-bold text-muted ps-4">Página</th>
              <th class="text-uppercase small fw-bold text-muted">Tipo</th>
              <th class="text-uppercase small fw-bold text-muted text-end pe-4">Visitas</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($top_pages as $page): ?>
              <tr>
                <td class="ps-4">
                  <div class="d-flex flex-column">
                    <span
                      class="fw-bold text-body"><?= htmlspecialchars($page->visitor_pages_title ?? 'Sin título') ?></span>
                    <a href="<?= htmlspecialchars($page->visitor_pages_uri) ?>" target="_blank"
                      class="small text-muted text-decoration-none">
                      <?= htmlspecialchars($page->visitor_pages_uri) ?>
                    </a>
                  </div>
                </td>
                <td>
                  <?php
                  $badgeColor = match ($page->visitor_pages_type) {
                    'page' => 'bg-primary',
                    'article' => 'bg-success',
                    'api' => 'bg-secondary',
                    'admin' => 'bg-danger',
                    default => 'bg-info'
                  };
                  ?>
                  <span class="badge rounded-pill bg-opacity-10 text-body <?= $badgeColor ?> text-opacity-75">
                    <?= ucfirst($page->visitor_pages_type) ?>
                  </span>
                </td>
                <td class="text-end pe-4 fw-bold text-primary">
                  <?= number_format($page->visitor_pages_total_views) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-transparent py-3">
        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
          <i class="fa-solid fa-laptop-code text-info"></i>
          Navegadores
        </h5>
      </div>
      <div class="card-body">
        <?php
        // Calcular total para sacar porcentajes
        $total_browser_hits = 0;
        foreach ($browsers as $b)
          $total_browser_hits += $b->total;
        ?>

        <div class="d-flex flex-column gap-4">
          <?php foreach ($browsers as $browser):
            $percent = ($total_browser_hits > 0) ? round(($browser->total / $total_browser_hits) * 100) : 0;
            // Icono según navegador
            $icon = match (strtolower($browser->visitor_browser)) {
              'chrome' => 'fa-brands fa-chrome text-danger',
              'firefox' => 'fa-brands fa-firefox-browser text-warning',
              'safari' => 'fa-brands fa-safari text-primary',
              'edge' => 'fa-brands fa-edge text-info',
              default => 'fa-solid fa-globe text-secondary'
            };
            ?>
            <div>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold text-muted small">
                  <i class="<?= $icon ?> me-1"></i> <?= $browser->visitor_browser ?: 'Desconocido' ?>
                </span>
                <span class="fw-bold small"><?= $percent ?>%</span>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percent ?>%"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header bg-transparent py-3">
        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
          <i class="fa-solid fa-user-clock text-warning"></i>
          Usuarios Registrados Recientemente
        </h5>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <tbody>
            <?php foreach ($recent_users as $user): ?>
              <tr>
                <td class="ps-4" style="width: 60px;">
                  <img src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" class="rounded-circle" width="40"
                    height="40" style="object-fit:cover;">
                </td>
                <td>
                  <div class="d-flex flex-column">
                    <span class="fw-bold text-body"><?= htmlspecialchars($user->user_login) ?></span>
                    <small class="text-muted"><?= htmlspecialchars($user->user_email) ?></small>
                  </div>
                </td>
                <td>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                    <?= htmlspecialchars($user->role_name) ?>
                  </span>
                </td>
                <td class="text-end pe-4 text-muted small">
                  <i class="fa-regular fa-calendar me-1"></i>
                  <?= date('d/m/Y', strtotime($user->user_created)) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>