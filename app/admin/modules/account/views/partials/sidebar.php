<div class="card sticky-top" style="top: 1rem; z-index: 10;">
  <div class="card-header p-3">
    <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Gestión de Cuenta</h6>
  </div>
  <div class="list-group list-group-flush">
    <a href="<?= admin_route("account/profile") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active(PATH_ADMIN . '/account/profile') ?>">
      <i class="fa-solid fa-user-tie fa-fw"></i>
      <span class="small fw-bold text-uppercase">Vista General</span>
    </a>
    <a href="<?= admin_route("account/settings/profile") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active(PATH_ADMIN . '/account/settings/profile') ?>">
      <i class="fa-solid fa-user-gear fa-fw"></i>
      <span class="small fw-bold text-uppercase">Información del Perfil</span>
    </a>
    <a href="<?= admin_route("account/settings/password") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active(PATH_ADMIN . '/account/settings/password') ?>">
      <i class="fa-solid fa-shield-halved fa-fw"></i>
      <span class="small fw-bold text-uppercase">Seguridad</span>
    </a>
    <a href="<?= admin_route("account/settings/api") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active(PATH_ADMIN . '/account/settings/api') ?>">
      <i class="fa-solid fa-key fa-fw"></i>
      <span class="small fw-bold text-uppercase">API Keys</span>
    </a>
  </div>
</div>
