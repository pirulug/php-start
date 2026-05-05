<div class="card sticky-top" style="top: 4rem;">
  <div class="card-header p-3">
    <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Área Personal</h6>
  </div>
  <div class="list-group list-group-flush">
    <a href="<?= front_route("account/profile") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active('account/profile') ?>">
      <i class="fa-solid fa-circle-user fa-fw"></i>
      <span class="small fw-bold text-uppercase">Vista General</span>
    </a>
    <a href="<?= front_route("account/settings/profile") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active('account/settings/profile') ?>">
      <i class="fa-solid fa-user-pen fa-fw"></i>
      <span class="small fw-bold text-uppercase">Ajustes de Perfil</span>
    </a>
    <a href="<?= front_route("account/settings/password") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active('account/settings/password') ?>">
      <i class="fa-solid fa-shield-halved fa-fw"></i>
      <span class="small fw-bold text-uppercase">Seguridad</span>
    </a>
    <a href="<?= front_route("account/settings/api") ?>"
      class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= is_active('account/settings/api') ?>">
      <i class="fa-solid fa-plug fa-fw"></i>
      <span class="small fw-bold text-uppercase">Conexiones API</span>
    </a>
  </div>
</div>