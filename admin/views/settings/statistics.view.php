<?php $theme->blockStart("style"); ?>
<?php $theme->blockEnd("style"); ?>

<?php $theme->blockStart("script"); ?>
<script src="<?= SITE_URL ?>/admin/assets/js/chartjs.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dataDay = <?php echo json_encode($daily); ?>;
    const ctxDay = document.getElementById('chartDay').getContext('2d');
    new Chart(ctxDay, {
      type: 'line',
      data: {
        labels: dataDay.map(d => d.visit_date),
        datasets: [{
          label: 'Registros por Día (Mes Actual)',
          data: dataDay.map(d => d.total),
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
          fill: false,
        }]
      },
      options: {
        scales: {
          x: { beginAtZero: true },
          y: { beginAtZero: true }
        }
      }
    });

    // Datos por Mes (solo meses del año actual)
    const dataMonth = <?php echo json_encode($monthly); ?>;
    const ctxMonth = document.getElementById('chartMonth').getContext('2d');
    new Chart(ctxMonth, {
      type: 'bar',
      data: {
        labels: dataMonth.map(d => d.month),
        datasets: [{
          label: 'Registros por Mes (Año Actual)',
          data: dataMonth.map(d => d.total),
          backgroundColor: 'rgba(153, 102, 255, 0.2)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        scales: {
          x: { beginAtZero: true },
          y: { beginAtZero: true }
        }
      }
    });

    // Datos por Año (últimos 10 años)
    const dataYear = <?php echo json_encode($yearly); ?>;
    const ctxYear = document.getElementById('chartYear').getContext('2d');
    new Chart(ctxYear, {
      type: 'bar',
      data: {
        labels: dataYear.map(d => d.year),
        datasets: [{
          label: 'Registros por Año (Últimos 10 Años)',
          data: dataYear.map(d => d.total),
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          borderColor: 'rgba(255, 159, 64, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        scales: {
          x: { beginAtZero: true },
          y: { beginAtZero: true }
        }
      }
    });
  });

  // ===========================================================================

  const pageComparative = <?= json_encode($page_comparative); ?>;

  const dates = Array.from(new Set(
    Object.values(pageComparative).flatMap(d => d.map(item => item.date))
  )).sort();

  const pages = Object.keys(pageComparative);

  const datasets = pages.map((page, index) => {
    const visits = dates.map(date => {
      const entry = pageComparative[page].find(item => item.date === date);
      return entry ? entry.total : 0; 
    });

    return {
      label: page,
      data: visits,
      borderColor: `hsl(${(index * 50) % 360}, 70%, 60%)`,
      backgroundColor: `hsl(${(index * 50) % 360}, 70%, 80%)`,
      fill: false,
      tension: 0.1
    };
  });

  const pageComparisonCtx = document.getElementById('pageComparisonChart').getContext('2d');
  const pageComparisonChart = new Chart(pageComparisonCtx, {
    type: 'line',
    data: {
      labels: dates,
      datasets: datasets
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          mode: 'index',
          intersect: false,
        }
      },
      scales: {
        x: {
          type: 'category',
          title: {
            display: true,
            text: 'Fecha'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Visitas'
          },
          beginAtZero: true
        }
      }
    }
  });
</script>
<?php $theme->blockEnd("script"); ?>

<?php require BASE_DIR . "/admin/views/partials/top.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/navbar.partial.php"; ?>

<div class="row">
  <div class="col-12">
    <div class="card mb-3">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered text-center m-0">
            <thead>
              <tr>
                <th>Hoy</th>
                <th>Ayer</th>
                <th>Esta Semana</th>
                <th>Semana Pasada</th>
                <th>Este Mes</th>
                <th>Mes Pasado</th>
                <th>Desde Siempre</th>
              </tr>
            </thead>
            <tbody>
              <tr class="fs-2">
                <td><?= $stats['today']; ?></td>
                <td><?= $stats['yesterday']; ?></td>
                <td><?= $stats['this_week']; ?></td>
                <td><?= $stats['last_week']; ?></td>
                <td><?= $stats['this_month']; ?></td>
                <td><?= $stats['last_month']; ?></td>
                <td>
                  <span class="fw-bolder">
                    <?= $stats['all_time']; ?>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card mb-3">
      <div class="card-header">
        <h2 class="card-title h5">Comparativa de Páginas</h2>
      </div>
      <div class="card-body">
        <canvas id="pageComparisonChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header">
        <h2 class="card-title h5">Gráfica por Día</h2>
      </div>
      <div class="card-body">
        <canvas id="chartDay" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header">
        <h2 class="card-title h5">Gráfica por Mes</h2>
      </div>
      <div class="card-body">
        <canvas id="chartMonth" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header">
        <h2 class="card-title h5">Gráfica por Año</h2>
      </div>
      <div class="card-body">
        <canvas id="chartYear" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
</div>


<?php require BASE_DIR . "/admin/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/bottom.partial.php"; ?>