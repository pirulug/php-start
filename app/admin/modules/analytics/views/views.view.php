<?php start_block('title'); ?>
Páginas Vistas
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Analítica', 'link' => admin_route('analytics/summary')],
  ['label' => 'Vistas']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" rel="stylesheet">
<style>
  .nav-pills-premium .nav-link {
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 8px 20px;
    color: #6c757d;
    transition: all 0.3s ease;
  }
  .nav-pills-premium .nav-link.active {
    background-color: #f05;
    color: #fff;
    box-shadow: 0 4px 12px rgba(255, 0, 85, 0.2);
  }
  .chart-container-views {
    height: 350px;
    width: 100%;
    position: relative;
  }
  .premium-table thead th {
    background-color: transparent;
    border-bottom: 2px solid rgba(0,0,0,0.05);
    color: #6c757d;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
  }
  [data-bs-theme="dark"] .premium-table thead th {
    border-bottom-color: rgba(255,255,255,0.05);
  }
</style>
<?php end_block() ?>

<?php
// Helper functions (inline)
$icons = [
  'chrome'  => 'fa-brands fa-chrome text-danger',
  'firefox' => 'fa-brands fa-firefox text-warning',
  'safari'  => 'fa-brands fa-safari text-info',
  'edge'    => 'fa-brands fa-edge text-primary',
  'windows' => 'fa-brands fa-windows text-primary',
  'apple'   => 'fa-brands fa-apple',
  'android' => 'fa-brands fa-android text-success',
  'linux'   => 'fa-brands fa-linux text-secondary'
];

$countryToCode = [
  'argentina' => 'ar', 'bolivia' => 'bo', 'brasil' => 'br', 'brazil' => 'br', 'chile' => 'cl', 'colombia' => 'co', 'ecuador' => 'ec', 'peru' => 'pe', 'venezuela' => 've',
  'canada' => 'ca', 'estados unidos' => 'us', 'usa' => 'us', 'mexico' => 'mx', 'espana' => 'es', 'spain' => 'es', 'alemania' => 'de', 'germany' => 'de', 'francia' => 'fr',
  'reino unido' => 'gb', 'uk' => 'gb', 'italia' => 'it', 'portugal' => 'pt'
];

function getIcon($val, $icons) {
  $val = strtolower($val ?? '');
  foreach ($icons as $key => $class) {
    if (strpos($val, $key) !== false) return "<i class='$class'></i>";
  }
  return '<i class="fa-solid fa-globe text-muted"></i>';
}

function getFlag($country, $codes) {
  $code = $codes[strtolower($country ?? '')] ?? 'xx';
  return "<span class='fi fi-$code rounded-circle'></span>";
}
?>

<div class="row g-3">
  <div class="col-12">
    <div class="card overflow-hidden mb-3">
      <div class="card-header bg-transparent pt-3 px-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Evolución de Tráfico</h6>
        <nav class="nav nav-pills nav-pills-premium">
          <a href="?range=day" class="nav-link <?= $range === 'day' ? 'active' : '' ?>">Diario</a>
          <a href="?range=week" class="nav-link <?= $range === 'week' ? 'active' : '' ?>">Semanal</a>
        </nav>
      </div>
      <div class="card-body px-3 pb-3">
        <div class="chart-container-views">
          <canvas id="trafficChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card overflow-hidden">
      <div class="card-header bg-transparent pt-3 px-3">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-history me-2 text-info"></i>Últimas Páginas Vistas</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table premium-table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th class="ps-3">Hora</th>
                <th>Visitante / Ubicación</th>
                <th>Tecnología</th>
                <th>Origen</th>
                <th class="pe-3">Página</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lastViews as $r): ?>
                <tr>
                  <td class="ps-3 fw-bold text-primary small"><?= date('H:i A', strtotime($r->visitor_last_visit)) ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <?= getFlag($r->visitor_country, $countryToCode) ?>
                      <span class="small fw-medium"><?= $r->visitor_country ?: 'Desconocido' ?></span>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex gap-2 fs-6">
                       <span title="<?= $r->visitor_browser ?>"><?= getIcon($r->visitor_browser, $icons) ?></span>
                       <span title="<?= $r->visitor_platform ?>"><?= getIcon($r->visitor_platform, $icons) ?></span>
                    </div>
                  </td>
                  <td>
                    <div class="text-truncate small text-muted" style="max-width: 150px;">
                      <?= $r->visitor_referer ?: '<span class="badge bg-secondary-subtle text-secondary small">Directo</span>' ?>
                    </div>
                  </td>
                  <td class="pe-3">
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge bg-info-subtle text-info border-0 rounded-pill px-3 fw-bold"><?= (int) $r->visitor_total_hits ?></span>
                      <span class="small text-truncate fw-bold" style="max-width: 300px;"><?= $r->page ?></span>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('trafficChart').getContext('2d');
  const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
  gradient1.addColorStop(0, 'rgba(255, 0, 85, 0.15)');
  gradient1.addColorStop(1, 'rgba(255, 0, 85, 0)');

  const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
  gradient2.addColorStop(0, 'rgba(52, 152, 219, 0.15)');
  gradient2.addColorStop(1, 'rgba(52, 152, 219, 0)');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [
        {
          label: 'Visitantes',
          data: <?= json_encode($visitorsJS) ?>,
          borderColor: '#f05',
          backgroundColor: gradient1,
          fill: true,
          tension: 0.45,
          borderWidth: 3,
          pointRadius: 0,
          hoverRadius: 6
        },
        {
          label: 'Vistas',
          data: <?= json_encode($viewsJS) ?>,
          borderColor: '#3498db',
          backgroundColor: gradient2,
          fill: true,
          tension: 0.45,
          borderWidth: 3,
          pointRadius: 0,
          hoverRadius: 6
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true, position: 'top', align: 'end', labels: { boxWidth: 8, usePointStyle: true, font: { weight: 'bold', family: '"Inter", sans-serif' } } },
        tooltip: { backgroundColor: '#1a1a1a', titleColor: '#fff', bodyColor: '#fff', padding: 12, cornerRadius: 10 }
      },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11, family: '"Inter", sans-serif' } } },
        y: { beginAtZero: true, grid: { borderDash: [3, 3], color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11, family: '"Inter", sans-serif' } } }
      }
    }
  });
</script>