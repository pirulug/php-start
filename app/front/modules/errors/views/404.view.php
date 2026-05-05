<?php start_block('title'); ?>
Página no encontrada (404)
<?php end_block(); ?>

<div class="container my-3 py-3">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 text-center">
      
      <div class="mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mb-4" style="width: 120px; height: 120px;">
          <i class="fa fa-circle-xmark display-3"></i>
        </div>
      </div>

      <h1 class="display-1 fw-bold text-primary mb-2">404</h1>
      <h2 class="fw-bold text-uppercase mb-3">¡Vaya! Parece que te has perdido</h2>
      <p class="text-body-secondary mb-5 fs-5">
        La página que estás buscando no existe o ha sido movida a una nueva ubicación. 
        No te preocupes, puedes volver al camino principal usando el botón de abajo.
      </p>

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="<?= APP_URL ?>" class="btn btn-primary px-5 py-3 text-uppercase small fw-bold">
          <i class="fa-solid fa-house me-2"></i> Ir al Inicio
        </a>
        <button onclick="history.back()" class="btn btn-outline-secondary px-5 py-3 text-uppercase small fw-bold">
          <i class="fa-solid fa-arrow-left me-2"></i> Volver Atrás
        </button>
      </div>

      <div class="mt-5 pt-4 border-top">
        <p class="small text-body-secondary mb-0 text-uppercase fw-bold">
          <i class="fa-solid fa-bug me-2"></i> Si crees que esto es un error, por favor contáctanos.
        </p>
      </div>

    </div>
  </div>
</div>