<?php require __DIR__ . "/_partials/top.partial.php"; ?>
<?php require __DIR__ . "/_partials/navbar.partial.php"; ?>

<div class="container">
  <div class="card my-3">
    <div class="card-body text-center">
      <h1 class="display-1 text-center">404</h1>
      <h2 class="text-center">Página no encontrada</h2>
      <p class="text-center">La página que buscas no existe o ha sido movida.</p>
      <a href="<?=$url->home()?>" class="btn btn-primary">Volver al inicio.</a>
    </div>
  </div>
</div>

<?php require __DIR__ . "/_partials/footer.partial.php"; ?>
<?php require __DIR__ . "/_partials/bottom.partial.php"; ?>