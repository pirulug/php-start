<?php
// Preloader autolimpiable para el Sitio Público
?>
<div class="show position-fixed translate-middle w-100 vh-100 top-50 start-50" id="spinner" style="background: var(--pr-body-bg); z-index: 99999;">
  <div class="loader-container">
    <div class="loader-circle"></div>
    <div class="loader-logo">
      <?php if ($config->favicon()): ?>
        <img src="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $config->favicon()->{'apple-touch-icon'} ?>" alt="Logo" width="40" height="40">
      <?php else: ?>
        <img src="<?= APP_URL ?>/static/assets/front/img/favicon/favicon.ico" alt="Logo" width="40" height="40">
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
@keyframes loader-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
@keyframes loader-pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: .8; transform: scale(1.1); }
}
.loader-container {
  align-items: center;
  display: flex;
  height: 100px;
  justify-content: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100px;
}
.loader-circle {
  animation: loader-spin 1s cubic-bezier(.68,-.55,.265,1.55) infinite;
  border: 4px solid transparent;
  border-radius: 50%;
  border-top: 4px solid var(--pr-primary, #ff0055);
  height: 100%;
  position: absolute;
  width: 100%;
}
.loader-circle:before {
  animation: loader-spin 1.5s linear infinite reverse;
  border: 4px solid transparent;
  border-radius: 50%;
  border-top: 4px solid rgba(255, 0, 85, 0.2);
  bottom: 8px;
  content: "";
  left: 8px;
  position: absolute;
  right: 8px;
  top: 8px;
}
.loader-circle:after {
  animation: loader-spin 1s cubic-bezier(.68,-.55,.265,1.55) infinite;
  border: 4px solid transparent;
  border-radius: 50%;
  border-top: 4px solid var(--pr-primary, #ff0055);
  bottom: -4px;
  content: "";
  left: -4px;
  opacity: .15;
  position: absolute;
  right: -4px;
  top: -4px;
}
.loader-logo {
  align-items: center;
  animation: loader-pulse 2s ease-in-out infinite;
  display: flex;
  justify-content: center;
  position: relative;
  z-index: 1;
}
.loader-logo img {
  transition: transform .3s ease;
}
#spinner.show {
  display: block !important;
}
#spinner:not(.show) {
  display: none !important;
}
</style>

<script>
  (function() {
    var spinner = document.getElementById("spinner");
    if (spinner) {
      window.addEventListener("load", function() {
        setTimeout(function() {
          spinner.classList.remove("show");
        }, 100);
      });
    }
  })();
</script>
