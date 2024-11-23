<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>

<div class="row">
  <div class="col-md-3 col-xl-4">
    <div class="card mb-3">
      <div class="card-body text-center">
        <img class="img-fluid rounded-circle mb-2" src="<?= getGravatar($user_session->user_email) ?>"
          alt="Christina Mason" width="128" height="128">
        <h5 class="card-title mb-0 text-uppercase"><?= $user->user_name ?></h5>
        <h6 class="text-muted mb-0">
          <?php if ($user->user_role == 0): ?>
            <span class="badge bg-danger-subtle">Super Admin</span>
          <?php elseif ($user->user_role == 1): ?>
            <span class="badge bg-info-subtle">Admin</span>
          <?php else: ?>
            <span class="badge bg-success-subtle">Usuario</span>
          <?php endif; ?>
        </h6>
      </div>
    </div>
  </div>
  <div class="col-md-9 col-xl-8">
    <div class="card mb-3">
      <div class="card-body h-100">
        <form action="" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Nombre de usuario</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Tu nombre de usuario"
              value="<?= $user->user_name ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" id="email" class="form-control" placeholder="Tu Email"
              value="<?= $user->user_email ?>" required>
          </div>
          <input type="hidden" name="id" value="<?= $user->user_id ?>">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title">Actividades</h5>
  </div>
  <div class="card-body">
    <?php foreach ($log->getLogsByUser($user->user_id) as $log): ?>
      <div class="d-flex align-items-start">
        <img class="rounded-circle me-2" src="<?= getGravatar($log->user_email) ?>" alt="<?= $log->user_name ?>"
          width="36" height="36">
        <div class="flex-grow-1">
          <small class="float-end text-navy"><?= tiempoDesdeCambio($log->timestamp) ?></small>
          <!-- <strong><?= $log->user_name ?></strong> -->
          <?= $log->description ?>
          <br>
          <small class="text-muted"><?= $log->timestamp ?></small>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/bottom.partial.php"; ?>