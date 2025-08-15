<?php $theme->blockStart("style"); ?>
<?php $theme->blockEnd("style"); ?>

<?php $theme->blockStart("script"); ?>
<?php $theme->blockEnd("script"); ?>

<?php require __DIR__ . "/_partials/top.partial.php"; ?>
<?php require __DIR__ . "/_partials/navbar.partial.php"; ?>

<div class="container">
  <div class="d-flex justify-content-center align-items-center">
    <div class="text-center">
      <h1 class="display-1 fw-bold"><?= SITE_NAME ?></h1>
      <p class="lead">Un sitio php para poder comenzar.</p>
    </div>
  </div>
</div>

<?php require __DIR__ . "/_partials/footer.partial.php"; ?>
<?php require __DIR__ . "/_partials/bottom.partial.php"; ?>