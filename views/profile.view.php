<?php require __DIR__ . "/_partials/top.partial.php"; ?>
<?php require __DIR__ . "/_partials/navbar.partial.php"; ?>

<div class="container mt-3">
  <div class="row">
    <div class="col-3">
      <div class="card">
        <div class="card-body">
          <img class="w-50 m-auto d-block rounded " src="<?= get_gravatar($user_session->user_email, 100) ?>" alt="">
          <i class="d-block text-center my-3">
            @admin
          </i>
          <hr>
          <dl>
            <dt>Name</dt>
            <dd><?= ucfirst($user_session->user_name) ?></dd>
            <dt>Correo electrónico</dt>
            <dd><?= $user_session->user_email ?></dd>
            <dt>Miembro desde</dt>
            <dd><?= $user_session->user_created ?></dd>
          </dl>
          <hr>
          <div class="d-grid">
            <a href="?action=edit" class="btn btn-secondary">
              Editar
              <i class="fa fa-pen"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-9">
      <div class="card">
        <div class="card-body">
          <h2 class="f2-bold fs-4">Mis</h2>
          <p>No se encontraron datos</p>
          <h2 class="f2-bold fs-4">Mi</h2>
          <p>No se encontraron datos</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . "/_partials/footer.partial.php"; ?>
<?php require __DIR__ . "/_partials/bottom.partial.php"; ?>