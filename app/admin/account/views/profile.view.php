<div class="card mb-3">
  <div class="card-body p-4">
    <div class="position-relative mb-5">
      <img class="img-fluid w-100 rounded" src="https://dummyimage.com/1200x300/ddd/000.jpg" alt="Cover Image">
      <div class="position-absolute top-100 start-50 translate-middle">
        <img class="img-fluid rounded-circle border border-3 border-white shadow"
          src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" width="170" height="170"
          alt="<?= $user->user_display_name ?>">
      </div>
    </div>
    <div class="pt-5">
      <h3 class="mb-1 text-center"><?= $user->user_display_name ?></h3>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Activities</h5>
  </div>
  <div class="card-body h-100">
    <div class="d-flex align-items-start mb-3">
      <img class="rounded-circle me-2" src="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" width="36"
        height="36" alt="<?= $user->user_display_name ?>">
      <div class="flex-grow-1">
        <small class="float-end text-primary">now</small>
        <strong><?= $user->user_display_name ?></strong>
        pushed new commits to <strong>GitHub</strong>
        <br>
        <small class="text-muted">Today</small>
      </div>
    </div>
    <hr>
    <div class="d-grid">
      <a class="btn btn-primary" href="https://github.com/Pirulug" target="_blank">See
        more on GitHub</a>
    </div>
  </div>
</div>