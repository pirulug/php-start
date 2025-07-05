<?php $theme->blockStart("script"); ?>
<script src="<?= $url_static->js("chartjs.js") ?>"></script>
<script>
  const ctx = document.getElementById('chartjs-dashboard-line').getContext('2d');
  //- const labels = Utils.months({count: 7});
  const myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', "Jul", "Ago", "Set", "Oct", "Nov", "Dic"],
      datasets: [
        {
          label: 'Buys',
          borderColor: '#ff0055',
          backgroundColor: ["rgba(215, 227, 244, 1)", "rgba(215, 227, 244, 0)"],
          data: [
            0,
            20,
            30,
            60,
            50,
            80,
            70,
            20,
            30,
            90,
            80,
            70
          ]
        },
        {
          label: 'Sales',
          borderColor: '#e67e22',
          backgroundColor: ["rgba(215, 227, 244, 1)", "rgba(215, 227, 244, 0)"],
          data: [
            0,
            90,
            30,
            10,
            20,
            70,
            10,
            60,
            50,
            80,
            70,
            80
          ]
        }
      ]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Bar chart
  new Chart(document.getElementById("chartjs-dashboard-bar"), {
    type: "bar",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dic"],
      datasets: [
        {
          label: "USA",
          backgroundColor: "#9b59b6",
          data: [80, 79, 76, 99, 55, 62, 48, 4, 55, 73, 45, 60],
          barPercentage: .75,
          categoryPercentage: .5
        },
        {
          label: "UK",
          backgroundColor: "#e67e22",
          data: [73, 55, 45, 76, 48, 55, 62, 80, 79, 4, 60, 99],
          barPercentage: .75,
          categoryPercentage: .5
        },
        {
          label: "PEN",
          backgroundColor: "#3498db",
          data: [48, 73, 4, 99, 79, 80, 62, 60, 76, 55, 55, 45],
          barPercentage: .75,
          categoryPercentage: .5
        },
      ]
    },
    options: {
      maintainAspectRatio: false,
      legend: {
        display: false
      },
    }
  });
</script>
<?php $theme->blockEnd("script"); ?>

<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

<div class="row g-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card border-left-primary mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">Usuarios</h5>
          </div>
          <div class="col-auto">
            <div class="stat stat-primary">
              <i class="align-middle" data-feather="users"></i>
            </div>
          </div>
        </div>
        <h1 class="mt-1 mb-0"><?= $count_user ?></h1>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">Visitas</h5>
          </div>
          <div class="col-auto">
            <div class="stat stat-success"><i class="align-middle" data-feather="users"></i></div>
          </div>
        </div>
        <h1 class="mt-1 mb-0"><?= $stats['today']; ?></h1>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card mb-3">
      <div class="card-body">

      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card mb-3">
      <div class="card-body">

      </div>
    </div>
  </div>
</div>


<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>