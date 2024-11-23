<?php blockStart("script"); ?>
<script src="<?= SITE_URL ?>/admin/assets/js/chartjs.js"></script>
<script>
  const ctx = document
    .getElementById("chartjs-dashboard-line")
    .getContext("2d");
  //- const labels = Utils.months({count: 7});
  const myChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: [
        "Ene",
        "Feb",
        "Mar",
        "Abr",
        "May",
        "Jun",
        "Jul",
        "Ago",
        "Set",
        "Oct",
        "Nov",
        "Dic",
      ],
      datasets: [
        {
          label: "Total de Visitas",
          borderColor: "#ff0055",
          backgroundColor: [
            "rgba(215, 227, 244, 1)",
            "rgba(215, 227, 244, 0)",
          ],
          data: [
            1000, 500, 48254, 4554, 85555, 364564, 457878, 45455, 2, 100,
            1545, 95454,
          ],
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });

  // Bar chart
  new Chart(document.getElementById("chartjs-dashboard-bar"), {
    type: "bar",
    data: {
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dic",
      ],
      datasets: [
        {
          label: "This year",
          backgroundColor: window.theme.primary,
          borderColor: window.theme.primary,
          hoverBackgroundColor: window.theme.primary,
          hoverBorderColor: window.theme.primary,
          data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
          barPercentage: 0.75,
          categoryPercentage: 0.5,
        },
      ],
    },
    options: {
      maintainAspectRatio: false,
      legend: {
        display: false,
      },
    },
  });
</script>
<?php blockEnd("script"); ?>

<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>

<div class="row g-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card border-left-primary mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">Sales</h5>
          </div>
          <div class="col-auto">
            <div class="stat stat-primary"><i class="align-middle" data-feather="truck"></i></div>
          </div>
        </div>
        <h1 class="mt-1 mb-3">2.382</h1>
        <div class="mb-0">
          <span class="text-danger">
            <i class="mdi mdi-arrow-bottom-right"></i>
            -3.65%
          </span>
          <span class="text-muted">Since last week</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">Visitors</h5>
          </div>
          <div class="col-auto">
            <div class="stat stat-success"><i class="align-middle" data-feather="users"></i></div>
          </div>
        </div>
        <h1 class="mt-1 mb-3">14.212</h1>
        <div class="mb-0">
          <span class="text-success">
            <i class="mdi mdi-arrow-bottom-right"></i>
            5.25%
          </span>
          <span class="text-muted">Since last week</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">Earnings</h5>
          </div>
          <div class="col-auto">
            <div class="stat stat-info"><i class="align-middle" data-feather="dollar-sign"></i></div>
          </div>
        </div>
        <h1 class="mt-1 mb-3">$21.300</h1>
        <div class="mb-0"><span class="text-success"><i class="mdi mdi-arrow-bottom-right"></i> 6.65%</span><span
            class="text-muted">Since last week</span></div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col mt-0">
            <h5 class="card-title">Orders</h5>
          </div>
          <div class="col-auto">
            <div class="stat stat-danger"><i class="align-middle" data-feather="shopping-cart"></i></div>
          </div>
        </div>
        <h1 class="mt-1 mb-3">64</h1>
        <div class="mb-0"><span class="text-danger"><i class="mdi mdi-arrow-bottom-right"></i> -2.25%</span><span
            class="text-muted">Since last week</span></div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-12 col-md-12 col-xxl-6 d-flex order-3 order-xxl-2">
    <div class="card mb-3 flex-fill w-100">
      <div class="card-header">
        <h5 class="card-title mb-0">Recent Movement</h5>
      </div>
      <div class="card-body py-3">
        <canvas id="chartjs-dashboard-line"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-12 col-xxl-6 d-flex order-3 order-xxl-2">
    <div class="card mb-3 flex-fill w-100">
      <div class="card-header">
        <h5 class="card-title mb-0">Real-Time</h5>
      </div>
      <div class="card-body px-4">
        <canvas id="chartjs-dashboard-bar" style="height: 350px"></canvas>
      </div>
    </div>
  </div>
</div>


<div class="card flex-fill overflow-hidden">
  <div class="card-header">
    <h5 class="card-title mb-0">Latest Projects</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover my-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Assignee</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Project Apollo</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-success">Done</span></td>
            <td>Vanessa Tucker</td>
          </tr>
          <tr>
            <td>Project Fireball</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-danger">Cancelled</span></td>
            <td>William Harris</td>
          </tr>
          <tr>
            <td>Project Hades</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-success">Done</span></td>
            <td>Sharon Lessman</td>
          </tr>
          <tr>
            <td>Project Nitro</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-warning">In progress</span></td>
            <td>Vanessa Tucker</td>
          </tr>
          <tr>
            <td>Project Phoenix</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-success">Done</span></td>
            <td>William Harris</td>
          </tr>
          <tr>
            <td>Project X</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-success">Done</span></td>
            <td>Sharon Lessman</td>
          </tr>
          <tr>
            <td>Project Romeo</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-success">Done</span></td>
            <td>Christina Mason</td>
          </tr>
          <tr>
            <td>Project Wombat</td>
            <td>01/01/2021</td>
            <td>31/06/2021</td>
            <td><span class="badge bg-warning">In progress</span></td>
            <td>William Harris</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>



<?php require BASE_DIR_ADMIN . "/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/bottom.partial.php"; ?>