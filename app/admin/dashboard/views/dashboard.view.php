<div class="row g-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card border-left-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Usuarios</h5>
        <h1><?= $count_user ?></h1>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card border-left-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Visitas</h5>
        <h1><?= $stats ?></h1>
      </div>
    </div>
  </div>
</div>

<?= $accessManager->debug_permissions(); ?>