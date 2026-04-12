<?php start_block('title'); ?>
Mi Perfil
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Cuenta']
]) ?>
<?php end_block(); ?>

<div class="row g-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top" style="top: 1rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Gestión de Cuenta</h6>
      </div>
      <div class="list-group list-group-flush">
        <a href="<?= admin_route("account/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'settings') === false ? 'active' : '' ?>">
          <i class="fa-solid fa-id-card fa-fw text-primary"></i>
          <span class="fw-bold fs-7">Vista General</span>
        </a>
        <a href="<?= admin_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-user-circle fa-fw"></i>
          <span class="fs-7">Información del Perfil</span>
        </a>
        <a href="<?= admin_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span class="fs-7">Seguridad y Contraseña</span>
        </a>
        <a href="<?= admin_route("account/settings/api") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-key fa-fw"></i>
          <span class="fs-7">API Keys</span>
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    
    <!-- TARJETA RESUMEN -->
    <div class="card mb-3 bg-primary-subtle bg-gradient overflow-hidden">
      <div class="card-body p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative">
            <img src="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>" 
                 class="rounded-circle border border-3 border-body"
                 style="width: 80px; height: 80px; object-fit: cover;" 
                 alt="Foto de <?= $user->user_display_name ?>">
            <span class="position-absolute bottom-0 end-0 bg-success border border-body border-2 rounded-circle" 
                  style="width: 15px; height: 15px;" title="En línea"></span>
          </div>
          <div>
            <h4 class="mb-1 fw-bold text-primary-emphasis"><?= $user->user_display_name ?></h4>
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="badge bg-primary px-2 py-1" style="font-size: 0.65rem;">
                <?= $user->role_name ?>
              </span>
              <span class="text-body-secondary small">
                <i class="fa-solid fa-at me-1"></i><?= $user->user_login ?>
              </span>
            </div>
            <div class="d-flex gap-2">
              <a href="<?= admin_route("account/settings/profile") ?>" class="btn btn-primary btn-sm px-3 text-uppercase small fw-bold">
                <i class="fa-solid fa-pencil me-1"></i> Editar Perfil
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- INFORMACIÓN DETALLADA -->
    <div class="card mb-3">
      <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0 fs-6 fw-bold text-uppercase">
          <i class="fa-solid fa-circle-info me-2 text-primary"></i>Datos de la Cuenta
        </h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <tbody>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold" style="width: 200px;">Correo Electrónico</td>
                <td class="pe-3 py-3 fw-medium text-body"><?= $user->user_email ?></td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Nombre de Usuario</td>
                <td class="pe-3 py-3 fw-medium text-body"><?= $user->user_login ?></td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Rol / Permisos</td>
                <td class="pe-3 py-3">
                  <span class="text-primary-emphasis fw-bold"><?= $user->role_name ?></span>
                </td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Último Acceso</td>
                <td class="pe-3 py-3 small text-body-secondary">
                  <i class="fa-regular fa-clock me-1"></i><?= $user->user_last_login ?>
                </td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Fecha de Registro</td>
                <td class="pe-3 py-3 small text-body-secondary">
                  <i class="fa-regular fa-calendar me-1"></i><?= $user->user_created ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- BLOQUE DEBUG -->
    <div class="card border-warning-subtle">
      <div class="card-header bg-warning-subtle bg-gradient border-bottom border-warning-subtle py-2">
        <h6 class="card-title mb-0 text-warning-emphasis small text-uppercase fw-bold">
          <i class="fa-solid fa-terminal me-2"></i>Permisos del Usuario (Debug)
        </h6>
      </div>
      <div class="card-body bg-body p-0">
        <pre class="m-0 p-3 text-body-secondary small"
          style="max-height: 150px; overflow-y: auto; font-family: var(--bs-font-monospace); font-size: 0.7rem;">
<?= debug_user_permissions_raw($connect); ?>
         </pre>
      </div>
    </div>

  </div>
</div>