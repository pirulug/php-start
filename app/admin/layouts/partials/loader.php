<div class="show position-fixed translate-middle w-100 vh-100 top-50 start-50" id="spinner">
  <div class="loader-container">
    <div class="loader-circle"></div>
    <div class="loader-logo">
      <?php if ($config->favicon()): ?>
        <img src="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'favicon-32x32'} ?>" alt="Logo"
          width="40" height="40">
      <?php else: ?>
        <img src="<?= APP_URL ?>/static/assets/admin/img/favicon/favicon.ico" alt="Logo" width="40" height="40">
      <?php endif; ?>
    </div>
  </div>
</div>