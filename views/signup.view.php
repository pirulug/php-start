<?php require __DIR__ . "/partials/top.partial.php"; ?>
<?php require __DIR__ . "/partials/navbar.partial.php"; ?>

<div class="container d-flex flex-column mt-3" style="">
  <div class="row">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title m-0 fs-2">Registro</h2>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">Usuario</label>
              <input type="text" class="form-control" id="username" name="username"
                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <!-- Botón de Registro -->
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . "/partials/footer.partial.php"; ?>
<?php require __DIR__ . "/partials/bottom.partial.php"; ?>