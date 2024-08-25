<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>



<div class="card mb-3">
  <div class="card-header">
    <h5 class="card-title">Cambiar contraseña</h5>
  </div>
  <div class="card-body">
    <form action="" method="POST">
      <div class="mb-3">
        <label for="current_password" class="form-label">Contraseña actual</label>
        <input type="password" name="current_password" id="current_password" class="form-control"
          placeholder="Ingresa tu contraseña actual" value="">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Nueva contraseña</label>
        <input type="password" name="password" id="password" class="form-control"
          placeholder="Ingresa la nueva contraseña" value="">
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirmar contraseña</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control"
          placeholder="Confirma la nueva contraseña" value="">
      </div>

      <div class="mb-3 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>

  </div>
</div>

<!-- <div class="card">
  <div class="card-header">
    <h5 class="card-title">Eliminar Cuenta</h5>
  </div>
  <div class="card-body">
    <form action="" method="get">
      <p>Tu cuenta será eliminada permanentemente y no se podrá recuperar, haz clic en "¡Tócame!" para continuar</p>
      <div class="form-check">
        <div class="checkbox">
          <input type="checkbox" id="iaggree" class="form-check-input">
          <label for="iaggree">¡Tócame! Si estás de acuerdo en eliminar permanentemente</label>
        </div>
      </div>
      <div class="form-group my-2 d-flex justify-content-end">
        <button type="submit" class="btn btn-danger" id="btn-delete-account" disabled="">Eliminar</button>
      </div>
    </form>
  </div>
</div> -->


<?php require BASE_DIR_ADMIN . "/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/bottom.partial.php"; ?>