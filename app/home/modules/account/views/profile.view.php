<?php start_block('title'); ?>
Mi Cuenta
<?php end_block(); ?>

<div class="container my-3">
<div class="row g-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top bg-body" style="top: 2rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Área Personal</h6>
      </div>
      <div class="list-group list-group-flush bg-transparent">
        <a href="<?= home_route("account/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3 <?= strpos($_SERVER['REQUEST_URI'], 'settings') === false ? 'active' : '' ?>">
          <i class="fa-solid fa-id-card fa-fw "></i>
          <span class="fw-bold ">Vista General</span>
        </a>
        <a href="<?= home_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-circle-user fa-fw"></i>
          <span class="">Ajustes de Perfil</span>
        </a>
        <a href="<?= home_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span class="">Privacidad y Seguridad</span>
        </a>
        <a href="<?= home_route("account/settings/api") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 mb-1 rounded-3">
            <i class="fa-solid fa-key fa-fw"></i>
          <span class="">Conexiones API</span>
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    
    <!-- HEADER DE PERFIL -->
    <div class="card mb-3 bg-primary-subtle bg-gradient overflow-hidden">
      <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
          <div class="position-relative">
            <img src="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>" 
                 class="rounded-circle border border-3 border-body"
                 style="width: 100px; height: 100px; object-fit: cover;" 
                 alt="<?= $user->user_display_name ?>">
            <span class="position-absolute bottom-0 end-0 bg-success border border-body border-2 rounded-circle" 
                  style="width: 18px; height: 18px;" title="Activo ahora"></span>
          </div>
          <div class="flex-grow-1">
            <h3 class="fw-bold mb-1 text-primary-emphasis"><?= $user->user_display_name ?></h3>
            <div class="mb-3">
              <span class="badge bg-primary text-uppercase px-2 py-1 me-2" style="font-size: 0.7rem;">
                <?= $user->role_name ?>
              </span>
              <span class="text-body-secondary small"><i class="fa-solid fa-at me-1"></i><?= $user->user_login ?></span>
            </div>
            <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
              <a href="<?= home_route("account/settings/profile") ?>" class="btn btn-primary btn-sm px-4 text-uppercase small fw-bold">
                <i class="fa-solid fa-user-gear me-2"></i> Configurar Perfil
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TABLA DE INFORMACIÓN -->
    <div class="card bg-body">
      <div class="card-header bg-transparent py-3 ps-4">
        <h5 class="card-title mb-0 fs-6 fw-bold text-uppercase text-body-secondary">Datos de tu Cuenta</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 bg-body">
            <tbody>
              <tr>
                <td class="ps-4 py-3 text-body-secondary  text-uppercase fw-bold" style="width: 220px;">Email de contacto</td>
                <td class="pe-4 py-3 fw-medium text-body"><?= $user->user_email ?></td>
              </tr>
              <tr>
                <td class="ps-4 py-3 text-body-secondary  text-uppercase fw-bold">Nombre completo registrado</td>
                <td class="pe-4 py-3 fw-medium text-body">
                  <?= trim(($usermeta->first_name ?? '') . ' ' . ($usermeta->last_name ?? '') . ' ' . ($usermeta->second_last_name ?? '')) ?: 'Sin registrar' ?>
                </td>
              </tr>
              <tr>
                <td class="ps-4 py-3 text-body-secondary  text-uppercase fw-bold">Nombre de usuario</td>
                <td class="pe-4 py-3 text-body"><?= $user->user_login ?></td>
              </tr>
              <tr>
                <td class="ps-4 py-3 text-body-secondary  text-uppercase fw-bold">Rango en la plataforma</td>
                <td class="pe-4 py-3 fw-bold text-primary-emphasis"><?= $user->role_name ?></td>
              </tr>
              <tr>
                <td class="ps-4 py-3 text-body-secondary  text-uppercase fw-bold">Fecha de alta</td>
                <td class="pe-4 py-3 small text-body-secondary">
                  <i class="fa-regular fa-calendar-check me-1"></i><?= date('d M, Y', strtotime($user->user_created)) ?>
                </td>
              </tr>
              <tr>
                <td class="ps-4 py-3 text-body-secondary  text-uppercase fw-bold">Última actividad detectada</td>
                <td class="pe-4 py-3 small text-body-secondary">
                  <i class="fa-regular fa-clock me-1"></i><?= $user->user_last_login ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
</div>
