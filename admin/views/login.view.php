<?php require "partials/top.partial.php"; ?>

<div class="container d-flex flex-column">
  <div class="row vh-100">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
      <div class="d-table-cell align-middle">
        <div class="text-center mt-4">
          <h1 class="h2"><?= SITE_NAME ?></h1>
          <p class="lead">Inicie sessión</p>
        </div>
        <div class="card">
          <div class="card-body">
            <?php $messageHandler->displayMessages(); ?>
            <div class="m-sm-3">
              <form method="POST">
                <div class="mb-3">
                  <label class="form-label">Usuario</label>
                  <input class="form-control" type="text" name="user-name" placeholder="Usuario" value="admin"
                    required />
                </div>
                <div class="mb-3">
                  <label class="form-label">Contraseña</label>
                  <input class="form-control" type="password" name="user-password" placeholder="Contraseña"
                    value="admin123" required />
                  <!-- <small><a href="/auth-reset-password.html">Forgot password?</a></small> -->
                </div>
                <div>
                  <div class="form-check align-items-center">
                    <input class="form-check-input" id="customControlInline" type="checkbox" name="remember-me"
                      checked />
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
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require "partials/dark-ligth.partial.php"; ?>
<?php require "partials/bottom.partial.php"; ?>