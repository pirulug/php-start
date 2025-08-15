<?php $theme->blockStart("style"); ?>
<?php $theme->blockEnd("style"); ?>

<?php $theme->blockStart("script"); ?>
<script src="<?= $static_url->assets("js","chartjs.js") ?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Datos por Día (mes actual)
    const dataDay = <?php echo json_encode($daily); ?>;
    const labelsDay = dataDay.map(item => item.dia);
    const valuesDay = dataDay.map(item => item.visitas);
    const ctxDay = document.getElementById('chartDay').getContext('2d');
    new Chart(ctxDay, {
      type: 'line',
      data: {
        labels: labelsDay,
        datasets: [{
          label: 'Visitas diarias',
          data: valuesDay,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Visitas por día (Mes Actual)'
          }
        }
      }
    });

    // Datos por Mes (solo meses del año actual)
    const dataMonth = <?php echo json_encode($monthly); ?>;
    const labelsMonth = Object.keys(dataMonth);
    const valuesMonth = Object.values(dataMonth);
    const ctxMonth = document.getElementById('chartMonth').getContext('2d');
    new Chart(ctxMonth, {
      type: 'bar',
      data: {
        labels: labelsMonth,
        datasets: [{
          label: 'Registros por Mes (Año Actual)',
          data: valuesMonth,
          backgroundColor: 'rgba(153, 102, 255, 0.2)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        scales: {
          x: { beginAtZero: true },
          y: { beginAtZero: true }
        },
        plugins: {
          title: {
            display: true,
            text: "Registros por Mes (Año Actual)"
          }
        }
      }
    });

    // Datos por Año (últimos 10 años)
    const dataYear = <?php echo json_encode($yearly); ?>;
    const labelsYear = Object.keys(dataYear);
    const valuesYear = Object.values(dataYear);
    const ctxYear = document.getElementById('chartYear').getContext('2d');
    new Chart(ctxYear, {
      type: 'bar',
      data: {
        labels: labelsYear,
        datasets: [{
          label: 'Registros por Año (Últimos 10 Años)',
          data: valuesYear,
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          borderColor: 'rgba(255, 159, 64, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        scales: {
          x: { beginAtZero: true },
          y: { beginAtZero: true }
        },
        plugins: {
          title: {
            display: true,
            text: 'Registros por Año (Últimos 10 Años)'
          }
        }
      },
    });


    // ===========================================================================

    // Comparativa de Páginas
    const pageComparative = <?= json_encode($page_comparative); ?>;

    const diasSemana = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
    const dates = diasSemana.filter(dia =>
      Object.values(pageComparative).some(pagina =>
        pagina.some(item => item.dia === dia)
      )
    );

    const pages = Object.keys(pageComparative);

    const datasets = pages.map((page, index) => {
      const visits = dates.map(dia => {
        const entry = pageComparative[page].find(item => item.dia === dia);
        return entry ? entry.visitas : 0;
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
              text: 'Día'
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

    // paginas siempre
    const alwaysPage = <?= json_encode($alwaysPage); ?>;
    const alwaysPageLabels = Object.keys(alwaysPage);
    const alwaysPageValues = Object.values(alwaysPage);

    const alwaysDataSets = [{
      label: 'Always Page Data',
      data: alwaysPageValues,  // Usar los valores directamente
      backgroundColor: alwaysPageLabels.map((apage, aindex) => `hsl(${(aindex * 50) % 360}, 70%, 60%)`),
    }];

    const alwaysPageCtx = document.getElementById('alwaysPageChart').getContext('2d');
    new Chart(alwaysPageCtx, {
      type: 'pie',
      data: {
        labels: alwaysPageLabels,
        datasets: alwaysDataSets
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Comparativa de Páginas'
          }
        }
      },
    });

  });
</script>
<?php $theme->blockEnd("script"); ?>

<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

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
  <div class="col-lg-6">
    <div class="card mb-3">
      <div class="card-body">
        <canvas id="alwaysPageChart" width="400" height="200"></canvas>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-body">
        <?php
        echo "<h3>Top 10 IPs</h3><ul>";
        foreach ($top_ips as $row) {
          echo "<li>{$row['ip_address']} — {$row['total_visits']} visitas</li>";
        }
        echo "</ul>";
        ?>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card mb-3">
      <div class="card-body">
        <canvas id="chartDay" width="400" height="200"></canvas>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <canvas id="chartMonth" width="400" height="200"></canvas>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <canvas id="chartYear" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
</div>


<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>