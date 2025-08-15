<?php $theme->blockStart("script"); ?>
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
        <h1 class="mt-1 mb-0"><?= $stats; ?></h1>
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