<?php start_block("title") ?>
Vistas
<?php end_block() ?>

<div class="card">
  <div class="card-body">
    <div class="mb-3">
      <a href="?range=day"
        class="btn btn-sm <?= $range === 'day' ? 'btn-primary' : 'btn-outline-primary' ?>">Diario</a>
      <a href="?range=week"
        class="btn btn-sm <?= $range === 'week' ? 'btn-primary' : 'btn-outline-primary' ?>">Semanal</a>
    </div>

    <canvas id="trafficChart" height="100"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      new Chart(document.getElementById('trafficChart'), {
        type: 'line',
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [
            {
              label: 'Visitantes',
              data: <?= json_encode($visitorsJS) ?>,
              tension: 0.3
            },
            {
              label: 'Vistas',
              data: <?= json_encode($viewsJS) ?>,
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    </script>

    <table class="table table-bordered table-hover align-middle mt-4">
      <thead>
        <tr>
          <th>Ver hora</th>
          <th>Información para visitantes</th>
          <th>Remitente</th>
          <th>Vistas totales</th>
          <th>Página</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lastViews as $r): ?>
          <tr>
            <td><?= date('H:i', strtotime($r->visitor_last_visit)) ?></td>
            <td>
              <?= $r->visitor_country ?> /
              <?= $r->visitor_platform ?> /
              <?= $r->visitor_device ?> /
              <?= $r->visitor_browser ?>
            </td>
            <td><?= $r->visitor_referer ?: 'Tráfico directo' ?></td>
            <td class="text-center"><?= (int) $r->visitor_total_hits ?></td>
            <td><?= $r->page ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>