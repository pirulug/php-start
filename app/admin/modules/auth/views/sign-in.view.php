<?php block_start("title") ?>
Iniciar Sesión
<?php block_end() ?>

<div class="text-center mb-4">
  <div class="auth-logo-box d-inline-flex align-items-center justify-content-center text-white rounded-3 mb-3"
    style="width: 44px; height: 44px">
    <i class="bi bi-layers"></i>
  </div>
  <h5 class="fw-bold mb-1"><?= __("Bienvenido de nuevo") ?></h5>
  <p class="text-body-secondary small"><?= __("Por favor ingresa tus datos para iniciar sesión.") ?></p>
</div>
<div class="card auth-card border-0 rounded-3">
  <div class="card-body p-4">

    <?= $notifier->showBootstrap(); ?>

    <form method="post" autocomplete="on">
      <div class="mb-3">
        <label class="form-label" for="user_login">
          <?= __("Usuario") ?>
        </label>
        <input class="form-control" id="user_login" type="text" name="user_login" placeholder="Ej: admin" required autofocus autocomplete="username" />
      </div>
      <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <label class="form-label" for="user_password">
            <?= __("Contraseña") ?>
          </label>
        </div>
        <div class="input-group">
          <input class="form-control" id="user_password" type="password" name="user_password" placeholder="Enter your password" required autocomplete="current-password" data-pr-toggle-password="" />
        </div>
      </div>
      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" id="rememberMe" type="checkbox" name="remember_me" checked="checked" />
          <label class="form-check-label text-body-secondary small" for="rememberMe">
            <?= __("Mantener sesión iniciada") ?>
          </label>
        </div>
      </div>
      <div class="d-grid">
        <button class="btn btn-primary" type="submit"><?= __("Iniciar Sesión") ?></button>
      </div>
    </form>
  </div>
</div>