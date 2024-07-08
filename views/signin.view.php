<?php require __DIR__ . "/partials/top.partial.php"; ?>
<?php require __DIR__ . "/partials/navbar.partial.php"; ?>

<div class="container">
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Usuario</label>
      <input class="form-control" type="text" name="user_name" placeholder="Usuario" value="test" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Contraseña</label>
      <input class="form-control" type="password" name="user_password" placeholder="Contraseña" value="test"
        required />
      <!-- <small><a href="/auth-reset-password.html">Forgot password?</a></small> -->
    </div>
    <div>
      <div class="form-check align-items-center">
        <input class="form-check-input" id="customControlInline" type="checkbox" value="remember-me" name="remember-me"
          checked="" />
        <label class="form-check-label text-small" for="customControlInline">
          Recordar
        </label>
      </div>
    </div>
    <div class="d-grid gap-2 mt-3">
      <button class="btn btn-primary" href="/">Sign in</button>
    </div>
  </form>
</div>

<?php require __DIR__ . "/partials/footer.partial.php"; ?>
<?php require __DIR__ . "/partials/bottom.partial.php"; ?>