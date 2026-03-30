<?php start_block("title") ?>
Configuración de Perfil
<?php end_block() ?>

<link rel="stylesheet" href="<?= APP_URL . "/static/plugins/dropzone/dropimg.css" ?>">

<div class="container mt-4">
  <div class="row g-4">

    <!-- SIDEBAR -->
    <div class="col-md-4 col-lg-3">
      <div class="card sticky-top" style="top: 6rem; z-index: 1;">
        <div class="card-header bg-transparent border-bottom p-3">
          <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Ajustes</h6>
        </div>
        <div class="list-group list-group-flush">
          <a href="<?= home_route("account/settings/profile") ?>" 
             class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-user-circle fa-fw"></i>
            <span>Mi Perfil</span>
          </a>
          <a href="<?= home_route("account/settings/password") ?>" 
             class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'password') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-shield-halved fa-fw"></i>
            <span>Seguridad</span>
          </a>
        </div>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="col-md-8 col-lg-9">
      <form enctype="multipart/form-data" method="POST">
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-transparent border-bottom py-3">
            <h5 class="card-title mb-0">Información del Perfil</h5>
          </div>

          <div class="card-body">
            <div class="mb-4 pb-4 border-bottom d-flex flex-column flex-sm-row align-items-center gap-4">
              <div>
                <h6 class="fw-bold mb-1 text-center text-sm-start">Foto de Perfil</h6>
                <p class="text-muted small mb-0 text-center text-sm-start">Haz clic en la imagen para cambiarla.</p>
              </div>
              <div class="ms-sm-auto">
                <input type="file" id="user_image" name="user_image" data-dropimg data-width="150" data-height="150"
                       data-default="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>"
                       accept=".jpg,.jpeg,.png,.gif,.webp">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label small text-muted">Nombre de Usuario</label>
                <div class="input-group">
                  <span class="input-group-text bg-body-tertiary"><i class="fa-solid fa-at text-muted"></i></span>
                  <input type="text" class="form-control bg-body-tertiary" value="<?= $user->user_login ?>" disabled readonly>
                </div>
                <div class="form-text small">Tu nombre de usuario es único y no se puede cambiar.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label small text-muted">Correo Electrónico</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent"><i class="fa-regular fa-envelope"></i></span>
                  <input type="email" name="user_email" class="form-control" value="<?= $user->user_email ?>" required>
                </div>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-12">
                <label class="form-label small text-muted">Nombres</label>
                <input type="text" name="user_first_name" class="form-control" value="<?= $usermeta->first_name ?? "" ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label small text-muted">Primer Apellido</label>
                <input type="text" name="user_last_name" class="form-control" value="<?= $usermeta->last_name ?? "" ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label small text-muted">Segundo Apellido</label>
                <input type="text" name="user_second_last_name" class="form-control" value="<?= $usermeta->second_last_name ?? "" ?>">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label small text-muted">Alias <span class="text-danger">*</span></label>
                <input type="text" name="user_nickname" class="form-control" value="<?= $user->user_nickname ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label small text-muted">Mostrar públicamente como</label>
                <select name="user_display_name" class="form-select">
                  <?php
                  $display_options = [
                    $user->user_login,
                    $user->user_nickname,
                    $usermeta->first_name ?? null,
                    $usermeta->last_name ?? null,
                    $usermeta->second_last_name ?? null,
                    trim(($usermeta->first_name ?? '') . ' ' . ($usermeta->last_name ?? '')),
                    trim(($usermeta->first_name ?? '') . ' ' . ($usermeta->last_name ?? '') . ' ' . ($usermeta->second_last_name ?? '')),
                  ];
                  $display_options = array_unique(array_filter($display_options));
                  foreach ($display_options as $option):
                    $selected = ($option === $user->user_display_name) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($option, ENT_QUOTES) . "\" $selected>$option</option>";
                  endforeach;
                  ?>
                </select>
              </div>
            </div>

            <input type="hidden" name="id" value="<?= $user->user_id ?>">
          </div>

          <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end py-3">
            <button name="update_profile" type="submit" class="btn btn-primary px-4 shadow-sm">
              <i class="fa-solid fa-save me-1"></i> Guardar Cambios
            </button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>

<script src="<?= APP_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();
</script>
