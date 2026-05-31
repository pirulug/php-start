<?php start_block("title") ?>
  404 - Página no encontrada
<?php end_block() ?>

<?php start_block("css") ?>
<style>
  .error-code {
    font-size: 8rem;
    line-height: 1;
    font-weight: 800;
  }
</style>
<?php end_block() ?>

<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
  <div class="text-center">
    <div class="mb-4">
      <i class="fa-solid fa-triangle-exclamation text-primary fa-5x"></i>
    </div>
    <h2 class="error-code text-body m-0 h1">404</h2>
    <h2 class="display-5 fw-bold mb-3">¡Vaya! Página no encontrada</h2>
    <p class="lead text-body mb-4 opacity-75">
      Lo sentimos, el recurso que buscas no existe o ha sido movido a otra ubicación.
    </p>
    <div class="d-flex justify-content-center gap-2">
      <a href="<?= admin_route('dashboard') ?>" class="btn btn-primary px-4 py-2 text-uppercase small fw-bold">
        <i class="fa-solid fa-house me-2"></i>
        Ir al Dashboard
      </a>
      <button onclick="history.back()" class="btn btn-outline-secondary px-4 py-2 text-uppercase small fw-bold">
        <i class="fa-solid fa-arrow-left me-2"></i>
        Regresar
      </button>
    </div>
  </div>
</div>