<ul class="nav nav-tabs card-header-tabs m-0">
  <li class="nav-item">
    <a class="nav-link <?= is_active(PATH_ADMIN . "/modules") ?>" href="<?= admin_route("modules") ?>">
      <i class="fa-solid fa-user-shield me-2"></i>
      Administración
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= is_active(PATH_ADMIN . "/modules/front") ?>" href="<?= admin_route("modules/front") ?>">
      <i class="fa-solid fa-laptop me-2"></i>
      Frontend
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= is_active(PATH_ADMIN . "/modules/api") ?>" href="<?= admin_route("modules/api") ?>">
      <i class="fa-solid fa-code me-2"></i>
      API Global
    </a>
  </li>
</ul>
