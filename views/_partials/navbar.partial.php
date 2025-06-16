<nav class="navbar navbar-expand-lg bg-body">
  <div class="container">
    <a class="navbar-brand" href="<?= SITE_URL ?>">
      <img class="brand-logo-ligth" src="<?= $url_static->logo($brand->st_whitelogo) ?>" alt="Logo Light" class="logo-light"
        height="40">
      <img class="brand-logo-dark" src="<?= $url_static->logo($brand->st_darklogo) ?>" alt="Logo Dark" class="logo-dark d-none"
        height="40">
    </a>
    <button class="navbar-toggler me-1 ms-auto" type="button" data-bs-toggle="offcanvas"
      data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-start" id="navbarResponsive">
      <div class="offcanvas-header">
        <a class="navbar-brand" href="<?= $url_static->home() ?>">
          <img class="brand-logo-ligth" src="<?= $url_static->logo($brand->st_whitelogo) ?>" alt="Logo Light"
            class="logo-light" height="40">
          <img class="brand-logo-dark" src="<?= $url_static->logo($brand->st_darklogo) ?>" alt="Logo Dark"
            class="logo-dark d-none" height="40">
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav align-items-lg-center justify-content-end flex-grow-1 pe-1">
          <li class="nav-item">
            <a class="nav-link" href="<?= $url_static->home() ?>">Inicio</a>
          </li>

        </ul>
      </div>
    </div>
    <ul class="navbar-nav">
      <li class="nav-item">
        <button class="nav-link py-2 d-flex align-items-center" id="bd-theme-toggle" type="button"><span
            class="theme-icon-active mr-1" id="bd-theme-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" class="feather feather-sun">
              <circle cx="12" cy="12" r="5"></circle>
              <line x1="12" y1="1" x2="12" y2="3"></line>
              <line x1="12" y1="21" x2="12" y2="23"></line>
              <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
              <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
              <line x1="1" y1="12" x2="3" y2="12"></line>
              <line x1="21" y1="12" x2="23" y2="12"></line>
              <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
              <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>
          </span>
        </button>
      </li>
    </ul>
  </div>
</nav>

<main class="flex-shrink-0">

  <?php if ($messageHandler->hasMessages()): ?>
    <div class="floating-message">
      <?= $messageHandler->displayMessages(); ?>
    </div>
  <?php endif; ?>