<?php start_block('title'); ?>
Información del Perfil
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Cuenta', 'link' => admin_route('account/profile')],
  ['label' => 'Editar Perfil']
]) ?>
<?php end_block(); ?>

<?php start_block('css'); ?>
<?= static_libs_css("dropzone", "dropimg.css") ?>
<?php end_block(); ?>

<?php start_block('js'); ?>
<?= static_libs_js("dropzone", "dropimg.js") ?>
<script>
  DropImg.init();
</script>
<?php end_block(); ?>


<div class="row g-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <?php require_once BASE_DIR . '/app/admin/modules/account/views/partials/sidebar.php'; ?>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    <form enctype="multipart/form-data" method="POST">
      <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
          <h6 class="card-title mb-0 fw-bold text-uppercase">
            <i class="fa-solid fa-user-edit me-2 text-primary"></i>Actualizar Perfil
          </h6>
        </div>

        <div class="card-body">

          <!-- SECCIÓN: FOTO DE PERFIL -->
          <div class="mb-3 pb-3 border-bottom d-flex align-items-center flex-wrap gap-3">
            <div class="ms-md-0 ms-auto order-md-1 order-2">
              <input type="file" id="user_image" name="user_image" data-dropimg data-width="120" data-height="120"
                data-default="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>"
                accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>
            <div class="order-md-2 order-1">
              <h6 class="fw-bold mb-1">Foto de Perfil</h6>
              <p class="text-body-secondary small mb-0">Esta imagen se mostrará en tu perfil y comentarios.</p>
              <div class="mt-2 text-body-secondary" style="font-size: 0.7rem;">Formatos permitidos: JPG, PNG, WEBP.
              </div>
            </div>
          </div>

          <!-- SECCIÓN: IDENTIDAD -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="user_login" class="form-label">Nombre de Usuario</label>
              <input type="text" id="user_login" class="form-control bg-body" value="<?= $user->user_login ?>" disabled
                readonly>
              <div class="form-text small mt-1">El nombre de usuario es un identificador único.</div>
            </div>
            <div class="col-md-6">
              <label for="user_email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
              <input type="email" id="user_email" name="user_email" class="form-control"
                value="<?= $user->user_email ?>" required>
            </div>
          </div>

          <!-- SECCIÓN: NOMBRES -->
          <div class="bg-body p-3 border rounded mb-3">
            <div class="row g-3">
              <div class="col-md-12">
                <label for="user_first_name" class="form-label">Nombres Protagonistas</label>
                <input type="text" id="user_first_name" name="user_first_name" class="form-control"
                  value="<?= $usermeta->first_name ?? "" ?>" placeholder="Tu nombre real">
              </div>
              <div class="col-md-6">
                <label for="user_last_name" class="form-label">Primer Apellido</label>
                <input type="text" id="user_last_name" name="user_last_name" class="form-control"
                  value="<?= $usermeta->last_name ?? "" ?>">
              </div>
              <div class="col-md-6">
                <label for="user_second_last_name" class="form-label">Segundo Apellido</label>
                <input type="text" id="user_second_last_name" name="user_second_last_name" class="form-control"
                  value="<?= $usermeta->second_last_name ?? "" ?>">
              </div>
            </div>
          </div>

          <!-- SECCIÓN: VISIBILIDAD -->
          <div class="row g-3">
            <div class="col-md-6">
              <label for="user_nickname" class="form-label">Alias / Nickname <span class="text-danger">*</span></label>
              <input type="text" id="user_nickname" name="user_nickname" class="form-control"
                value="<?= $user->user_nickname ?>" required>
            </div>
            <div class="col-md-6">
              <label for="user_display_name" class="form-label">Mostrar públicamente como</label>
              <select id="user_display_name" name="user_display_name" class="form-select fw-bold text-primary">
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
      </div>

      <!-- BOTONERA STICKY -->
      <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom mt-3">
        <a href="<?= admin_route("account/profile") ?>"
          class="btn btn-outline-secondary px-4 text-uppercase small fw-bold">
          <i class="fa-solid fa-arrow-left me-2"></i> Cancelar
        </a>
        <button name="update_profile" type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Cambios
        </button>
      </div>
    </form>
  </div>

</div>