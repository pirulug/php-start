<nav class="sidebar js-sidebar" id="sidebar">
  <div class="sidebar-content js-simplebar">

    <!-- BRAND (FIJO, NO DINÁMICO) -->
    <a class="sidebar-brand" href="/<?php echo admin_route('dashboard'); ?>">
      <span class="sidebar-brand-text align-middle"><?= $config->siteName() ?? APP_NAME ?></span>
    </a>

    <!-- MENÚ -->
    <ul class="sidebar-nav">
      <?php Sidebar::render(); ?>
    </ul>

  </div>
</nav>