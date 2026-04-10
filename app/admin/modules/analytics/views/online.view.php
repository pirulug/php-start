<?php start_block('title'); ?>
Usuarios en Línea
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Analítica', 'link' => admin_route('analytics/summary')],
  ['label' => 'Online']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" rel="stylesheet">
<style>
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
// Helper functions (inline for now to maintain view autonomy)
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

<div class="card overflow-hidden">
  <div class="card-header bg-transparent py-3 px-3 d-flex justify-content-between align-items-center">
    <h6 class="fw-bold mb-0"><i class="fa-solid fa-bolt me-2 text-success"></i>Usuarios Online en Tiempo Real</h6>
    <span class="badge bg-success-subtle text-success fw-bold px-3">LIVE</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table premium-table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Actividad</th>
            <th>Visitante</th>
            <th>Ubicación</th>
            <th>Tecnología</th>
            <th>Página Actual</th>
            <th class="text-end pe-3">Remitente</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($onlineVisitors)): ?>
            <tr>
              <td colspan="6" class="text-center py-5 text-muted">No hay usuarios activos en este momento.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($onlineVisitors as $row): ?>
              <tr>
                <td class="ps-3">
                   <div class="small fw-bold text-primary"><?= date('H:i:s', strtotime($row->visitor_useronline_last_activity)) ?></div>
                   <div class="text-muted" style="font-size: 0.7rem;">Hace poco</div>
                </td>
                <td>
                  <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-3 font-monospace">
                    <?= $row->visitor_useronline_ip ?? 'HIDDEN' ?>
                  </span>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <?= getFlag($row->visitor_country, $countryToCode) ?>
                    <span class="small fw-medium"><?= $row->visitor_country ?: 'Desconocido' ?></span>
                  </div>
                </td>
                <td>
                  <div class="d-flex gap-2 fs-6">
                    <span title="<?= $row->visitor_browser ?>"><?= getIcon($row->visitor_browser, $icons) ?></span>
                    <span title="<?= $row->visitor_platform ?>"><?= getIcon($row->visitor_platform, $icons) ?></span>
                  </div>
                </td>
                <td>
                  <div class="text-truncate" style="max-width: 250px;" title="<?= $row->visitor_page_title ?>">
                    <span class="small fw-bold"><?= $row->visitor_page_title ?: '—' ?></span>
                  </div>
                </td>
                <td class="text-end pe-3">
                  <?php if ($row->visitor_useronline_referer): ?>
                    <a href="<?= $row->visitor_useronline_referer ?>" target="_blank" class="text-decoration-none small text-muted">
                      <i class="fa-solid fa-link me-1"></i> <?= parse_url($row->visitor_useronline_referer, PHP_URL_HOST) ?>
                    </a>
                  <?php else: ?>
                    <span class="badge bg-secondary-subtle text-secondary small">Directo</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>