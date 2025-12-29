<div class="row g-4">

  <div class="col-md-4 col-xl-3">
    <div class="card">
      <div class="card-body text-center">

        <div class="mt-3 mb-4">
          <img src="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>" class="rounded-circle img-fluid border p-1"
            style="width: 150px; height: 150px; object-fit: cover;" alt="Foto de <?= $user->user_display_name ?>">
        </div>

        <h4 class="mb-1 fw-bold"><?= $user->user_display_name ?></h4>
        <div class="mb-4">
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
            <?= $user->role_name ?>
          </span>
        </div>

        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
          <div class="text-start">
            <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Último Acceso</small>
            <span class="fw-medium small">
              <i class="fa-regular fa-clock me-1 text-secondary"></i>
              <?= $user->user_last_login ?>
            </span>
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
          <div class="text-start">
            <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Miembro Desde</small>
            <span class="fw-medium small">
              <i class="fa-regular fa-calendar me-1 text-secondary"></i>
              <?= $user->user_created ?>
            </span>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="col-md-8 col-xl-9">

    <div class="card mb-4">
      <div class="card-header bg-transparent border-bottom py-3">
        <h5 class="card-title mb-0 d-flex align-items-center">
          <i class="fa-regular fa-id-card me-2 text-primary"></i>
          Información de Cuenta
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-4">

          <div class="col-sm-6">
            <div class="d-flex align-items-start">
              <div class="me-3 mt-1 text-secondary">
                <i class="fa-solid fa-user-tag fa-lg"></i>
              </div>
              <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Nombre de Usuario</small>
                <div class="fs-6 fw-medium"><?= $user->user_login ?></div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="d-flex align-items-start">
              <div class="me-3 mt-1 text-secondary">
                <i class="fa-regular fa-envelope fa-lg"></i>
              </div>
              <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Correo Electrónico</small>
                <div class="fs-6 fw-medium text-break"><?= $user->user_email ?></div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="d-flex align-items-start">
              <div class="me-3 mt-1 text-secondary">
                <i class="fa-solid fa-shield-halved fa-lg"></i>
              </div>
              <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Nivel de Permisos</small>
                <div class="fs-6 fw-medium"><?= $user->role_name ?></div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="d-flex align-items-start">
              <div class="me-3 mt-1 text-secondary">
                <i class="fa-solid fa-signal fa-lg"></i>
              </div>
              <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Estado</small>
                <div class="text-success fw-bold">
                  <i class="fa-solid fa-circle fa-2xs me-1"></i> Activo
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="card border-warning-subtle">
      <div class="card-header bg-warning-subtle border-bottom border-warning-subtle py-2">
        <h6 class="card-title mb-0 text-warning-emphasis small text-uppercase fw-bold">
          <i class="fa-solid fa-bug me-2"></i>Debug: Permisos del Usuario
        </h6>
      </div>
      <div class="card-body bg-body-tertiary p-0">
        <pre class="m-0 p-3 text-body-secondary small"
          style="max-height: 200px; overflow-y: auto; font-family: var(--bs-font-monospace);">
<?= debug_user_permissions_raw($connect); ?>
         </pre>
      </div>
    </div>

  </div>
</div>