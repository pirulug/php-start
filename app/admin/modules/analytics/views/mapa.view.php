<?php start_block("title") ?>
Mapa de visitas
<?php end_block() ?>

<form method="get" class="row g-2 mb-3 align-items-end">
  <div class="col-md-3">
    <label class="form-label">Rango</label>
    <select name="range_type" class="form-select" onchange="this.form.submit()">
      <option value="day" <?= $rangeType === 'day' ? 'selected' : '' ?>>Hoy</option>
      <option value="specific_day" <?= $rangeType === 'specific_day' ? 'selected' : '' ?>>Día específico</option>
      <option value="week" <?= $rangeType === 'week' ? 'selected' : '' ?>>Semana</option>
      <option value="month" <?= $rangeType === 'month' ? 'selected' : '' ?>>Mes</option>
      <option value="year" <?= $rangeType === 'year' ? 'selected' : '' ?>>Año</option>
      <option value="custom" <?= $rangeType === 'custom' ? 'selected' : '' ?>>Personalizado</option>
    </select>
  </div>

  <div class="col-md-2">
    <label class="form-label">Día</label>
    <input type="date" name="date_day" value="<?= $dateDay ?>" class="form-control">
  </div>

  <div class="col-md-2">
    <label class="form-label">Desde fecha</label>
    <input type="date" name="date_from" value="<?= $dateFrom ?>" class="form-control">
  </div>

  <div class="col-md-2">
    <label class="form-label">Hasta fecha</label>
    <input type="date" name="date_to" value="<?= $dateTo ?>" class="form-control">
  </div>

  <div class="col-md-1">
    <label class="form-label">Hora desde</label>
    <select name="hour_from" class="form-select">
      <option value="">--</option>
      <?php for ($i = 0; $i < 24; $i++): ?>
        <option value="<?= $i ?>" <?= (string) $hourFrom === (string) $i ? 'selected' : '' ?>>
          <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>:00
        </option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="col-md-1">
    <label class="form-label">Hora hasta</label>
    <select name="hour_to" class="form-select">
      <option value="">--</option>
      <?php for ($i = 0; $i < 24; $i++): ?>
        <option value="<?= $i ?>" <?= (string) $hourTo === (string) $i ? 'selected' : '' ?>>
          <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>:59
        </option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="col-md-1 d-grid">
    <button class="btn btn-primary">Filtrar</button>
  </div>
</form>

<div id="visitorsMap" style="width:100%; height:440px;"></div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>

<script>
  const visitorsData = <?= json_encode($mapData) ?>;

  new jsVectorMap({
    selector: '#visitorsMap',
    map: 'world',
    zoomButtons: true,

    labels: { regions: { render: () => null } },

    regionStyle: {
      initial: {
        fill: 'var(--bs-secondary-bg)',
        stroke: 'var(--bs-border-color)'
      },
      hover: {
        fill: 'var(--bs-primary)'
      }
    },

    series: {
      regions: [{
        values: visitorsData,
        scale: ['var(--bs-primary-bg-subtle)', 'var(--bs-primary)'],
        normalizeFunction: 'polynomial'
      }]
    },

    onRegionTooltipShow: function (tooltip, code) {
      const n = visitorsData[code] ? visitorsData[code] : 0;
      tooltip.text(code + ' — ' + n + ' visitas');
    }
  });
</script>