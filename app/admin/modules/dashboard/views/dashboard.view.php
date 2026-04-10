<?php start_block("title"); ?>
Dashboard Principal
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<style>
  .welcome-banner {
    background: linear-gradient(135deg, #f05 0%, #ff1e7c 100%);
    color: #fff;
    border-radius: 12px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
  }
  .welcome-banner::after {
    content: '';
    position: absolute;
    top: -20px;
    right: -20px;
    width: 150px;
    height: 150px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
  }

  .quick-action-card {
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
  }
  .quick-action-card:hover {
    transform: translateY(-5px);
    border-color: #f05 !important;
  }
  .quick-action-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.25rem;
    margin-bottom: 1rem;
  }

  .system-item {
    padding: 1rem;
    border-radius: 10px;
    background-color: rgba(0,0,0,0.02);
  }
  [data-bs-theme="dark"] .system-item {
    background-color: rgba(255,255,255,0.02);
  }
  
  .user-avatar {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  [data-bs-theme="dark"] .user-avatar {
    border-color: #2b3035;
  }
</style>
<?php end_block() ?>

<div class="welcome-banner mb-3">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h2 class="fw-bold mb-1">¡Bienvenido al Panel Administrativo!</h2>
      <p class="mb-0 opacity-75">Controla tu plataforma de manera sencilla y eficiente.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
      <div class="small fw-bold text-uppercase opacity-75"><?= date('l, d F Y') ?></div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <!-- KPI: Usuarios -->
  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body p-3 d-flex align-items-center">
        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 me-3">
          <i class="fa-solid fa-users fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted small fw-bold text-uppercase mb-0">Usuarios</h6>
          <h3 class="mb-0 fw-bold"><?= number_format($count_user) ?></h3>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI: Roles -->
  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body p-3 d-flex align-items-center">
        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3">
          <i class="fa-solid fa-shield-halved fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted small fw-bold text-uppercase mb-0">Roles</h6>
          <h3 class="mb-0 fw-bold"><?= number_format($count_roles) ?></h3>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI: Módulos -->
  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body p-3 d-flex align-items-center">
        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 me-3">
          <i class="fa-solid fa-cubes fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted small fw-bold text-uppercase mb-0">Módulos</h6>
          <h3 class="mb-0 fw-bold"><?= number_format($modules_count) ?></h3>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI: PHP -->
  <div class="col-6 col-xl-3">
    <div class="card h-100">
      <div class="card-body p-3 d-flex align-items-center">
        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 me-3">
          <i class="fa-brands fa-php fa-lg"></i>
        </div>
        <div>
          <h6 class="text-muted small fw-bold text-uppercase mb-0">Versión PHP</h6>
          <h3 class="mb-0 fw-bold"><?= explode('-', PHP_VERSION)[0] ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Columna Izquierda: Acciones y Sistema -->
  <div class="col-lg-8">
    
    <div class="card mb-3">
      <div class="card-header bg-transparent px-3 pt-3">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-bolt me-2 text-primary"></i>Accesos Directos</h6>
      </div>
      <div class="card-body px-3 pb-3">
        <div class="row g-3">
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('users/create') ?>" class="card quick-action-card border-light shadow-none text-center p-3">
              <div class="quick-action-icon bg-primary bg-opacity-10 text-primary mx-auto">
                <i class="fa-solid fa-user-plus"></i>
              </div>
              <span class="small fw-bold text-uppercase">Nuevo Usuario</span>
            </a>
          </div>
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('settings') ?>" class="card quick-action-card border-light shadow-none text-center p-3">
              <div class="quick-action-icon bg-success bg-opacity-10 text-success mx-auto">
                <i class="fa-solid fa-gears"></i>
              </div>
              <span class="small fw-bold text-uppercase">Ajustes</span>
            </a>
          </div>
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('roles') ?>" class="card quick-action-card border-light shadow-none text-center p-3">
              <div class="quick-action-icon bg-info bg-opacity-10 text-info mx-auto">
                <i class="fa-solid fa-user-lock"></i>
              </div>
              <span class="small fw-bold text-uppercase">Roles</span>
            </a>
          </div>
          <div class="col-md-3 col-6">
            <a href="<?= admin_route('settings/backups') ?>" class="card quick-action-card border-light shadow-none text-center p-3">
              <div class="quick-action-icon bg-warning bg-opacity-10 text-warning mx-auto">
                <i class="fa-solid fa-database"></i>
              </div>
              <span class="small fw-bold text-uppercase">Respaldos</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
       <div class="card-header bg-transparent px-3 pt-3">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-server me-2 text-info"></i>Estado del Sistema</h6>
      </div>
      <div class="card-body px-3 pb-3">
        <div class="row g-3">
          <div class="col-md-6">
             <div class="system-item d-flex justify-content-between align-items-center h-100">
                <div>
                  <div class="small text-muted fw-bold">Memoria en Uso</div>
                  <div class="h5 mb-0 fw-bold"><?= $system_info['memory_usage'] ?></div>
                </div>
                <div class="text-primary"><i class="fa-solid fa-memory fa-2x opacity-25"></i></div>
             </div>
          </div>
          <div class="col-md-6">
             <div class="system-item d-flex justify-content-between align-items-center h-100">
                <div>
                  <div class="small text-muted fw-bold">Entorno</div>
                  <div class="small fw-bold"><?= $system_info['os'] ?> · <?= $system_info['server_software'] ?></div>
                </div>
                <div class="text-success"><i class="fa-solid fa-microchip fa-2x opacity-25"></i></div>
             </div>
          </div>
          <div class="col-12">
            <div class="system-item">
              <div class="d-flex justify-content-between mb-2">
                <span class="small text-muted fw-bold">Uso de Almacenamiento</span>
                <span class="small fw-bold"><?= $disk_percentage ?>%</span>
              </div>
              <div class="progress" style="height: 8px;">
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
      <div class="card-header bg-transparent px-3 pt-3">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-user-clock me-2 text-warning"></i>Usuarios Recientes</h6>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
          <?php foreach ($recent_users as $user): ?>
            <div class="list-group-item px-3 py-3 bg-transparent">
              <div class="d-flex align-items-center">
                <img src="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>" class="user-avatar me-3" alt="<?= $user->user_login ?>">
                <div class="flex-grow-1 overflow-hidden">
                  <div class="fw-bold text-truncate"><?= htmlspecialchars($user->user_login) ?></div>
                  <div class="text-muted small text-truncate"><?= htmlspecialchars($user->user_email) ?></div>
                </div>
                <div class="text-end ms-2">
                  <span class="badge bg-secondary-subtle text-secondary small px-2"><?= $user->role_name ?></span>
                  <div class="text-muted" style="font-size: 0.7rem;"><?= date('d M', strtotime($user->user_created)) ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="card-footer bg-transparent border-0 text-center py-3">
        <a href="<?= admin_route('users') ?>" class="text-decoration-none small fw-bold text-uppercase">Ver todos los usuarios</a>
      </div>
    </div>
  </div>

</div>