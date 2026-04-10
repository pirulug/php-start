<?php start_block('title'); ?>
Mapa de Visitantes
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Analítica', 'link' => admin_route('analytics/summary')],
  ['label' => 'Mapa']
]) ?>
<?php end_block(); ?>

<div class="card overflow-hidden mb-3">
  <div class="card-header bg-transparent pt-3 px-3">
    <h6 class="fw-bold mb-0"><i class="fa-solid fa-filter me-2 text-primary"></i>Filtros de Análisis Geográfico</h6>
  </div>
  <div class="card-body px-3 pb-3">
    <form method="get" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label small fw-bold text-uppercase text-muted">Rango de Tiempo</label>
        <select name="range_type" class="form-select shadow-none" onchange="this.form.submit()">
          <option value="day" <?= $rangeType === 'day' ? 'selected' : '' ?>>Hoy</option>
          <option value="specific_day" <?= $rangeType === 'specific_day' ? 'selected' : '' ?>>Día específico</option>
          <option value="week" <?= $rangeType === 'week' ? 'selected' : '' ?>>Esta Semana</option>
          <option value="month" <?= $rangeType === 'month' ? 'selected' : '' ?>>Este Mes</option>
          <option value="year" <?= $rangeType === 'year' ? 'selected' : '' ?>>Este Año</option>
          <option value="custom" <?= $rangeType === 'custom' ? 'selected' : '' ?>>Personalizado</option>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label small fw-bold text-uppercase text-muted">Día</label>
        <input type="date" name="date_day" value="<?= $dateDay ?>" class="form-control shadow-none">
      </div>

      <div class="col-md-2">
        <label class="form-label small fw-bold text-uppercase text-muted">Desde</label>
        <input type="date" name="date_from" value="<?= $dateFrom ?>" class="form-control shadow-none">
      </div>

      <div class="col-md-2">
        <label class="form-label small fw-bold text-uppercase text-muted">Hasta</label>
        <input type="date" name="date_to" value="<?= $dateTo ?>" class="form-control shadow-none">
      </div>

      <div class="col-md-1">
        <label class="form-label small fw-bold text-uppercase text-muted">H. Inicio</label>
        <select name="hour_from" class="form-select shadow-none">
          <option value="">--</option>
          <?php for ($i = 0; $i < 24; $i++): ?>
            <option value="<?= $i ?>" <?= (string) $hourFrom === (string) $i ? 'selected' : '' ?>>
              <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>:00
            </option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="col-md-1">
        <label class="form-label small fw-bold text-uppercase text-muted">H. Fin</label>
        <select name="hour_to" class="form-select shadow-none">
          <option value="">--</option>
          <?php for ($i = 0; $i < 24; $i++): ?>
            <option value="<?= $i ?>" <?= (string) $hourTo === (string) $i ? 'selected' : '' ?>>
              <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>:59
            </option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="col-md-1 d-grid">
        <button class="btn btn-primary fw-bold text-uppercase small py-2">Filtrar</button>
      </div>
    </form>
  </div>
</div>

<div class="card overflow-hidden">
  <div class="card-body p-0">
    <div id="visitorsMap" style="width:100%; height:500px; background-color: transparent;"></div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>

<script>
  const visitorsData = <?= json_encode($mapData) ?>;
  const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

  new jsVectorMap({
    selector: '#visitorsMap',
    map: 'world',
    zoomButtons: true,

    labels: { regions: { render: () => null } },

    regionStyle: {
      initial: {
        fill: isDark ? '#3d444b' : '#e9ecef',
        stroke: isDark ? '#2b3035' : '#dee2e6',
        strokeWidth: 1.5,
        fillOpacity: 1
      },
      hover: {
        fill: '#f05',
        fillOpacity: 0.8
      }
    },

    series: {
      regions: [{
        values: visitorsData,
        scale: ['#ffccd5', '#f05'],
        normalizeFunction: 'polynomial'
      }]
    },

    onRegionTooltipShow: function (tooltip, code) {
      const n = visitorsData[code] ? visitorsData[code] : 0;
      tooltip.text(
        `<div style="padding: 10px; background: #1a1a1a; color: #fff; border-radius: 8px; border: 1px solid #333;">
          <b style="color: #f05;">${code}</b><br>
          <span style="font-size: 1.2rem; font-weight: bold;">${n}</span> visitas
        </div>`, 
        true
      );
    }
  });
</script>