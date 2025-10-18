<div class="container my-3">
  <form action="" method="post">

    <div class="d-flex justify-content-center">
      <div class="card" style="width: 48rem;">
        <div class="card-header text-center">
          <h3 class="mb-0">Registrarse</h3>
        </div>

        <div class="card-body">

          <div class="mb-3">
            <label for="user_email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="user_email" name="email" placeholder="example@mail.com"
              required>
          </div>

          <div class="mb-3">
            <label for="user_name" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="user_name" name="username" placeholder="Nombre de Usuario"
              required>
          </div>

          <div class="mb-3">
            <label for="user_password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="user_password" name="password" placeholder="Contraseña"
              required>
          </div>

          <div class="mb-3">
            <label for="re_user_password" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="re_user_password" name="password_confirmation"
              placeholder="Vuelve a escribir la Contraseña" required>
          </div>

          <div class="mb-3 text-center">
            <!-- <div class="g-recaptcha" data-sitekey="6LeOfCoUAAAAAPbdDj7EZmmbxCCxIkXPSOx18I8J" data-theme="light"></div> -->
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Registrarse</button>
          </div>
        </div>

        <div class="card-footer text-center">
          <small>¿Ya tienes una cuenta? <a href="<?= SITE_URL ?>/signin">Inicia sesión aquí</a></small>
        </div>
      </div>
    </div>
  </form>
</div>