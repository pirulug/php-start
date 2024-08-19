<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>

<?php display_messages(); ?>

<div class="mb-3">
  <div class="bg-body rounded p-3 text-center position-relative">
    <img class="img-fluid rounded-circle mb-2" src="<?= getGravatar($user_session->user_email) ?>" alt="Christina Mason"
      width="128" height="128">
    <h5 class="card-title mb-0 text-uppercase"><?= $_SESSION['user_name'] ?></h5>
    <h6 class="text-muted mb-0">
      <?php if ($_SESSION['user_role'] == 0): ?>
        <span class="badge bg-danger-subtle">Super Admin</span>
      <?php elseif ($_SESSION['user_role'] == 1): ?>
        <span class="badge bg-info-subtle">Admin</span>
      <?php else: ?>
        <span class="badge bg-success-subtle">Usuario</span>
      <?php endif; ?>
    </h6>
    <a href="#" class="h4">
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <i class="fa fa-pen"></i>
      </span>
    </a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header">
    <h5 class="card-title">Actividades</h5>
  </div>
  <div class="card-body">
    <div class="d-flex align-items-start">
      <img class="rounded-circle me-2" src="<?= getGravatar($user_session->user_email) ?>"
        alt="<?= $_SESSION['user_name'] ?>" width="36" height="36">
      <div class="flex-grow-1">
        <small class="float-end text-navy">1h ago</small>
        <strong>Christina Mason</strong>
        posted a new blog
        <br>
        <small class="text-muted">Today 6:35 pm</small>
      </div>
    </div>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/bottom.partial.php"; ?>