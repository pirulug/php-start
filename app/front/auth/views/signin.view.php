<div class="container my-3">

  <form action="" method="post" accept-charset="utf-8">

    <div class="d-flex justify-content-center">
      <div class="card" style="width: 48rem;">
        <div class="card-header text-center">
          <h3 class="mb-0">Iniciar Sesión</h3>
        </div>

        <div class="card-body">
          <div class="mb-3">
            <label for="user_login" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="user_login" name="email" placeholder="Email" required>
          </div>

          <div class="mb-3">
            <label for="user_password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="user_password" name="password" placeholder="Contraseña"
              required>
          </div>

          <div class="mb-3 d-flex justify-content-between align-items-center">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
              <label class="form-check-label" for="remember_me">
                Recordarme
              </label>
            </div>
            <button type="submit" name="sign_in" class="btn btn-primary">
              <i class="fa-solid fa-arrow-right-to-bracket"></i>
              Iniciar Sesión
            </button>
          </div>
        </div>

        <div class="card-footer text-center">
          <div class="d-flex justify-content-between">
            <a href="<?= SITE_URL ?>/reset-password" class="btn btn-outline-secondary">
              <i class="fa fa-key"></i>
              Resetear Contraseña
            </a>
            <a href="<?= SITE_URL ?>/signup" class="btn btn-outline-primary">
              <i class="fa fa-plus"></i>
              Regístrate Aquí
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>

</div>
