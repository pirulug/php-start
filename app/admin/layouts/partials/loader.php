<div
  class="show bg-body position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"
  id="spinner" style="z-index: 9999;">
  <div class="position-relative d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 5rem; height: 5rem;" role="status">
      <!-- <span class="sr-only">Loading...</span> -->
    </div>
    <?php if ($config->favicon()): ?>
      <img src="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'favicon-32x32'} ?>"
        class="position-absolute rounded-circle" style="width: 2.5rem; height: 2.5rem; object-fit: contain;" alt="Logo">
    <?php else: ?>
      <img src="<?= APP_URL ?>/static/assets/img/favicon/favicon.ico" class="position-absolute rounded-circle"
        style="width: 2.5rem; height: 2.5rem; object-fit: contain;" alt="Logo">
    <?php endif; ?>
  </div>
</div>