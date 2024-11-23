<?php require BASE_DIR_ADMIN . "/views/partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/partials/navbar.partial.php"; ?>



<div class="card mb-3">
  <div class="card-body">
    <form id="formNewUser" enctype="multipart/form-data" action="" method="post">

      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="user_name" class="form-control"
          value="<?= isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : '' ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="text" name="user_email" class="form-control"
          value="<?= isset($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : '' ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" type="text" name="user_password"
          value="<?= isset($_POST['user_password']) ? htmlspecialchars($_POST['user_password']) : '' ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Role</label>
        <select class="form-select" name="user_role" required>
          <option value="">- Seleccionar -</option>
          <option value="2" <?= isset($_POST['user_role']) && $_POST['user_role'] == 2 ? 'selected' : '' ?>>
            Administrador
          </option>
          <option value="3" <?= isset($_POST['user_role']) && $_POST['user_role'] == 3 ? 'selected' : '' ?> selected>
            Usuario
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="user_status" required>
          <option value="0">- Seleccionar -</option>
          <option value="1" <?= isset($_POST['user_status']) && $_POST['user_status'] == 1 ? 'selected' : '' ?> selected>
            Activo
          </option>
          <option value="2" <?= isset($_POST['user_status']) && $_POST['user_status'] == 2 ? 'selected' : '' ?>>
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