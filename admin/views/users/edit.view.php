<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>



<div class="card mb-3">
  <div class="card-body">
    <form id="formNewUser" enctype="multipart/form-data" action="" method="post">

    <input type="hidden" name="user_id" value="<?= $encryption->encrypt($user->user_id) ?>">

      <div class="mb-3">
        <label>Name</label>
        <input type="text" name="user_name" class="form-control" value="<?= $user->user_name ?>" required>
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="text" name="user_email" class="form-control" value="<?= $user->user_email ?>" required>
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input class="form-control" type="text" name="user_password" value="<?= $encryption->decrypt($user->user_password) ?>">
      </div>

      <div class="mb-3">
        <label class="control-label">Role</label>
        <select class="form-select" name="user_role" required>
          <option value="0">- Seleccionar -</option>
          <option value="1" <?= $user->user_role == 1 ? 'selected' : '' ?>>
            Administrador
          </option>
          <option value="2" <?= $user->user_role == 2 ? 'selected' : '' ?>>
            Suscriptor
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label class="control-label">Status</label>
        <select class="form-select" name="user_status" required>
          <option value="0">- Seleccionar -</option>
          <option value="1" <?= $user->user_status == 1 ? 'selected' : '' ?>>
            Activo
          </option>
          <option value="2" <?= $user->user_status == 2 ? 'selected' : '' ?>>
            Inactivo
          </option>
        </select>
      </div>

      <hr>
      <button class="btn btn-primary" type="submit" name="save">Guardar</button>
    </form>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/bottom.partial.php"; ?>