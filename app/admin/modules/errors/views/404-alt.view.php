<?php start_block("title") ?>
  Recurso no encontrado
<?php end_block() ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
      <div class="card border">
        <div class="card-body p-5 text-center">
          <div class="mb-4">
            <i class="fa-solid fa-bug text-danger fa-4x"></i>
          </div>
          <h2 class="display-4 fw-bold">404</h2>
          <h3 class="mb-3">Algo salió mal</h3>
          <p class="text-body opacity-75 mb-4">
            No pudimos encontrar la página que estabas buscando. Por favor, verifica la URL o intenta navegar desde el menú principal.
          </p>
          <div class="d-grid gap-2">
            <a href="<?= admin_route('dashboard') ?>" class="btn btn-primary text-uppercase small fw-bold py-2">
              <i class="fa-solid fa-gauge-high me-2"></i>
              Volver al inicio
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>