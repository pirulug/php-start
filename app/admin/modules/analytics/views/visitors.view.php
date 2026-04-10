<?php start_block('title'); ?>
Listado de Visitantes
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Analítica', 'link' => admin_route('analytics/summary')],
  ['label' => 'Visitantes']
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

<div class="card overflow-hidden">
  <div class="card-header bg-transparent py-3 px-3">
    <h6 class="fw-bold mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>Historial de Visitantes</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table premium-table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">Fecha / Hora</th>
            <th>Ubicación</th>
            <th>Tecnología</th>
            <th>Referencia</th>
            <th>Páginas (Entrada/Salida)</th>
            <th class="text-center pe-3">Visitas</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($visitorsTable as $row): ?>
            <tr>
              <td class="ps-3">
                <div class="small fw-bold text-dark"><?= date('d M, Y', strtotime($row->visitor_last_visit)) ?></div>
                <div class="text-muted small"><?= date('H:i A', strtotime($row->visitor_last_visit)) ?></div>
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
                <div class="text-truncate small text-muted" style="max-width: 150px;">
                  <?= $row->visitor_referer ?: '<span class="badge bg-secondary-subtle text-secondary">Directo</span>' ?>
                </div>
              </td>
              <td>
                <div class="small mb-1"><i class="fa-solid fa-arrow-right-to-bracket me-1 text-success opacity-50"></i> <span class="text-truncate d-inline-block align-bottom" style="max-width: 200px;"><?= $row->visitor_session_start_page ?: '—' ?></span></div>
                <div class="small"><i class="fa-solid fa-arrow-right-from-bracket me-1 text-danger opacity-50"></i> <span class="text-truncate d-inline-block align-bottom" style="max-width: 200px;"><?= $row->visitor_session_end_page ?: '—' ?></span></div>
              </td>
              <td class="text-center pe-3">
                <span class="badge bg-primary-subtle text-primary border-0 rounded-pill px-3 fw-bold">
                  <?= (int) $row->visitor_total_hits ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>