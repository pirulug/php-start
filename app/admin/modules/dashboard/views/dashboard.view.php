<?php start_block("title"); ?>
<?= __('Panel de Control') ?>
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => __('Dashboard')]
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<style>
  .welcome-banner {
    background: linear-gradient(135deg, var(--pr-primary) 0%, #6610f2 100%);
    color: #fff;
    padding: 2rem;
    position: relative;
    overflow: hidden;
  }

  .welcome-banner::after {
    content: '';
    position: absolute;
    top: -30px;
    right: -30px;
    width: 180px;
    height: 180px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  .quick-action-card {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
    border: 1px solid var(--bs-border-color);
  }

  .quick-action-card:hover {
    transform: translateY(-5px);
    border-color: var(--pr-primary) !important;
    background-color: var(--pr-primary-bg-subtle);
  }

  .quick-action-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    font-size: 1.4rem;
    margin: 0 auto 1rem;
  }

  .system-item {
    padding: 1.25rem;
    border-radius: 12px;
    background-color: var(--pr-tertiary-bg);
    border: 1px solid var(--pr-border-color-translucent);
  }

  .user-avatar {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid var(--pr-body-bg);
  }

  .kpi-icon {
    width: 54px;
    height: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
  }

  .progress-slim {
    height: 6px;
    border-radius: 10px;
  }
</style>
<?php end_block() ?>

<div class="welcome-banner rounded mb-3">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h2 class="fw-bold mb-1 text-white"><?= __('Bienvenido') ?>, <?= htmlspecialchars($user_session->user_login) ?>!</h2>
      <p class="mb-0 opacity-75"><?= __('Bienvenido al panel administrativo') ?></p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
      <div class="small fw-bold text-uppercase opacity-75 d-flex align-items-center justify-content-md-end gap-2">
        <i class="fa-regular fa-calendar"></i>
        <?= format_date() ?>
      </div>
    </div>
  </div>
</div>

<!-- KPIs -->
<div class="row g-3 mb-3">
  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center">
        <div class="kpi-icon bg-primary-subtle text-primary me-3">
          <i class="fa-solid fa-users fa-xl"></i>
        </div>
        <div>
          <span class="text-body-secondary small fw-bold text-uppercase d-block mb-1"><?= __('Usuarios Totales') ?></span>
          <h3 class="mb-0 fw-bold"><?= format_number($count_user) ?></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center">
        <div class="kpi-icon bg-success-subtle text-success me-3">
          <i class="fa-solid fa-shield-halved fa-xl"></i>
        </div>
        <div>
          <span class="text-body-secondary small fw-bold text-uppercase d-block mb-1"><?= __('Roles') ?></span>
          <h3 class="mb-0 fw-bold"><?= format_number($count_roles) ?></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center">
        <div class="kpi-icon bg-warning-subtle text-warning me-3">
          <i class="fa-solid fa-file-pen fa-xl"></i>
        </div>
        <div>
          <span class="text-body-secondary small fw-bold text-uppercase d-block mb-1"><?= __('Contenidos') ?></span>
          <h3 class="mb-0 fw-bold"><?= format_number($count_posts) ?></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body d-flex align-items-center">
        <div class="kpi-icon bg-info-subtle text-info me-3">
          <i class="fa-solid fa-hard-drive fa-xl"></i>
        </div>
        <div>
          <span class="text-body-secondary small fw-bold text-uppercase d-block mb-1"><?= __('Disco') ?></span>
          <h3 class="mb-0 fw-bold"><?= $disk_percentage ?>%</h3>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Columna Izquierda: Acciones y Sistema -->
  <div class="col-lg-8">

    <div class="card mb-3">
      <div class="card-header">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-bolt me-2 text-primary"></i><?= __('Accesos Directos') ?></h6>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('users/create') ?>" class="card quick-action-card text-center p-3">
              <div class="quick-action-icon bg-primary-subtle text-primary">
                <i class="fa-solid fa-user-plus"></i>
              </div>
              <span class="small fw-bold text-uppercase"><?= __('Nuevo Usuario') ?></span>
            </a>
          </div>
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('settings/general') ?>" class="card quick-action-card text-center p-3">
              <div class="quick-action-icon bg-success-subtle text-success">
                <i class="fa-solid fa-gears"></i>
              </div>
              <span class="small fw-bold text-uppercase"><?= __('Ajustes') ?></span>
            </a>
          </div>
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('roles') ?>" class="card quick-action-card text-center p-3">
              <div class="quick-action-icon bg-info-subtle text-info">
                <i class="fa-solid fa-user-lock"></i>
              </div>
              <span class="small fw-bold text-uppercase"><?= __('Roles') ?></span>
            </a>
          </div>
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('settings/backups') ?>" class="card quick-action-card text-center p-3">
              <div class="quick-action-icon bg-warning-subtle text-warning">
                <i class="fa-solid fa-database"></i>
              </div>
              <span class="small fw-bold text-uppercase"><?= __('Respaldos') ?></span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-server me-2 text-info"></i><?= __('Estado del Sistema') ?></h6>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="system-item d-flex justify-content-between align-items-center h-100">
              <div>
                <span class="small text-body-secondary fw-bold text-uppercase d-block mb-1"><?= __('Memoria en Uso') ?></span>
                <div class="h4 mb-0 fw-bold"><?= $system_info['memory_usage'] ?></div>
              </div>
              <div class="text-primary opacity-50"><i class="fa-solid fa-memory fa-2x"></i></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="system-item d-flex justify-content-between align-items-center h-100">
              <div>
                <span class="small text-body-secondary fw-bold text-uppercase d-block mb-1"><?= __('Entorno') ?></span>
                <div class="small fw-bold text-truncate" style="max-width: 180px;">
                    <?= $system_info['os'] ?> <br>
                    <span class="opacity-75"><?= $system_info['server_software'] ?></span>
                </div>
              </div>
              <div class="text-success opacity-50"><i class="fa-solid fa-microchip fa-2x"></i></div>
            </div>
          </div>
          <div class="col-12">
            <div class="system-item">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small text-body-secondary fw-bold text-uppercase"><?= __('Uso de Almacenamiento') ?></span>
                <span class="badge bg-info-subtle text-info fw-bold"><?= $disk_percentage ?>%</span>
              </div>
              <div class="progress progress-slim">
                <div class="progress-bar bg-info" style="width: <?= $disk_percentage ?>%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Columna Derecha: Usuarios Recientes -->
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-user-clock me-2 text-warning"></i><?= __('Actividad Reciente') ?></h6>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
          <?php foreach ($recent_users as $user): ?>
            <div class="list-group-item px-3 py-3 bg-transparent">
              <div class="d-flex align-items-center">
                <img src="<?= storage_uploads($user_session->user_image, "user") ?>" class="user-avatar me-3 border"
                  alt="<?= $user->user_login ?>">
                <div class="flex-grow-1 overflow-hidden">
                  <div class="fw-bold text-truncate text-body">
                    <?= htmlspecialchars($user->user_login) ?>
                  </div>
                  <div class="text-body-secondary small text-truncate">
                    <?= htmlspecialchars($user->user_email) ?>
                  </div>
                </div>
                <div class="text-end ms-2">
                  <div class="text-body-secondary" style="font-size: 0.75rem;">
                    <?= format_date($user->user_created) ?>
                  </div>
                  <span class="badge bg-body-secondary text-body-secondary small border" style="font-size: 0.65rem;"><?= $user->role_name ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="card-footer text-center py-3">
        <a href="<?= admin_route('users') ?>" class="btn btn-link btn-sm text-decoration-none p-0 fw-bold text-uppercase small">
          <?= __('Gestionar Usuarios') ?> <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>

</div>