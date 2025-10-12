<?php $theme->blockStart("style"); ?>
<link rel="stylesheet" href="<?= SITE_URL . "/static/plugins/dropzone/dropimg.css" ?>">
<?php $theme->blockEnd("style"); ?>

<div class="tab tab-vertical">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-bs-toggle="pill" data-bs-target=".tab-account" role="tab"
        aria-selected="true">
        <i class="fa fa-user-circle me-2"></i>
        Account
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="pill" data-bs-target=".tab-security" role="tab"
        aria-selected="false">
        <i class="fa fa-shield me-2"></i>
        Security
      </a>
    </li>
  </ul>
  <!-- Tab Content-->
  <div class="tab-content">
    <!-- Account-->
    <div class="tab-pane fade show active tab-account" role="tabpanel">
      <h4 class="tab-title">
        <i class="fa fa-user me-2"></i>
        Account Settings
      </h4>
      <form enctype="multipart/form-data" method="POST">
        <div class="mb-3">
          <figure class="text-center">
            <input type="file" id="user_image" name="user_image" data-dropimg data-width="100" data-height="100"
              data-default="<?= SITE_URL ?>/uploads/user/<?= $user->user_image ?>" accept=".jpg,.jpeg,.png,.gif,.webp">
          </figure>
        </div>

        <div class="mb-3">
          <label for="user_name" class="form-label">Nombre de usuario</label>
          <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Tu nombre de usuario"
            value="<?= $user->user_name ?>" disabled>
        </div>

        <div class="mb-3">
          <label for="user_first_name">Nombre</label>
          <input type="text" name="user_first_name" id="user_first_name" class="form-control"
            value="<?= $user->user_first_name ?>">
        </div>

        <div class="mb-3">
          <label for="user_last_name">Apellidos</label>
          <input type="text" name="user_last_name" id="user_last_name" class="form-control"
            value="<?= $user->user_last_name ?>">
        </div>

        <div class="mb-3">
          <label for="user_nickname">Alias (obligatorio)</label>
          <input type="text" name="user_nickname" id="user_nickname" class="form-control"
            value="<?= $user->user_nickname ?>" required>
        </div>

        <div class="mb-3">
          <label for="user_display_name" class="form-label">Mostrar este nombre públicamente</label>
          <select name="user_display_name" id="user_display_name" class="form-select">
            <?php
            // Posibles opciones de nombre público (orden como WordPress)
            $display_options = [
              $user->user_name, // nombre de usuario
              $user->user_nickname,
              $user->user_first_name,
              $user->user_last_name,
              trim($user->user_first_name . ' ' . $user->user_last_name),
              trim($user->user_last_name . ' ' . $user->user_first_name),
            ];

            // Elimina duplicados y valores vacíos
            $display_options = array_unique(array_filter($display_options));

            // Genera las opciones del select
            foreach ($display_options as $option):
              $selected = ($option === $user->user_display_name) ? 'selected="selected"' : '';
              echo "<option value=\"" . htmlspecialchars($option, ENT_QUOTES) . "\" $selected>$option</option>";
            endforeach;
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="user_email" class="form-label">Email</label>
          <input type="text" name="user_email" id="user_email" class="form-control" placeholder="Tu Email"
            value="<?= $user->user_email ?>" required>
        </div>

        <input type="hidden" name="id" value="<?= $user->user_id ?>">

        <button name="update_profile" type="submit" class="btn btn-primary">Guardar Cambios</button>

      </form>
    </div>
    <!-- Security-->
    <div class="tab-pane fade tab-security" role="tabpanel">
      <h6 class="tab-title mb-3">
        <i class="fa fa-lock me-2"></i>
        Security
      </h6>
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
          <button name="change_password" type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $theme->blockStart("script"); ?>
<script src="<?= SITE_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();
</script>
<?php $theme->blockEnd("script"); ?>