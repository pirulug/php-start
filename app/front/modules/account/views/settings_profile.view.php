<?php start_block("title") ?>
Ajustes de Perfil
<?php end_block() ?>

<?php start_block("css") ?>
<?= static_libs_css("dropzone", "dropimg.css") ?>
<?php end_block() ?>

<?php start_block("js") ?>
<?= static_libs_js("dropzone","dropimg.js") ?>
<script>
  DropImg.init();
</script>
<?php end_block() ?>

<div class="container my-3">
  <div class="row g-3">

    <!-- SIDEBAR DE NAVEGACIÓN -->
    <div class="col-md-4 col-lg-3">
      <?php require_once BASE_DIR . '/app/front/modules/account/views/partials/sidebar.php'; ?>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="col-md-8 col-lg-9">
      <form enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
        <div class="card mb-3">
          <div class="card-header py-3">
            <h6 class="card-title mb-0 fw-bold text-uppercase">
              <i class="fa-solid fa-user-edit me-2 text-primary"></i>Actualiza tu Perfil
            </h6>
          </div>

          <div class="card-body">

            <!-- SECCIÓN: FOTO -->
            <div class="mb-3 pb-3 border-bottom d-flex flex-column flex-md-row align-items-center gap-3">
              <div class="order-2 order-md-1">
                <input type="file" id="user_image" name="user_image" data-dropimg data-width="120" data-height="120"
                  data-default="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>"
                  accept=".jpg,.jpeg,.png,.gif,.webp">
              </div>
              <div class="order-1 order-md-2 text-center text-md-start">
                <h6 class="fw-bold mb-1">Imagen de Perfil</h6>
                <p class="text-body-secondary small mb-0">Esta foto es visible para otros miembros de la comunidad.</p>
                <div class="mt-2 text-body-secondary" style="font-size: 0.7rem;">Formatos: JPG, PNG, WEBP</div>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <!-- IDENTIDAD -->
              <div class="col-md-6">
                <label for="user_login" class="form-label">Nombre de Usuario</label>
                <input type="text" id="user_login" class="form-control bg-body" value="<?= $user->user_login ?>"
                  disabled readonly>
                <div class="form-text small mt-1">Identificador único del sistema (no editable).</div>
              </div>

              <!-- EMAIL -->
              <div class="col-md-6">
                <label for="user_email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" id="user_email" name="user_email" class="form-control"
                  value="<?= $user->user_email ?>" required>
              </div>
            </div>

            <!-- BLOQUE: DATOS PERSONALES -->
            <div class="p-3 bg-body border rounded mb-3">
              <div class="row g-3">
                <div class="col-12">
                  <label for="user_first_name" class="form-label">Tu(s) Nombre(s)</label>
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

            <!-- VISIBILIDAD -->
            <div class="row g-3">
              <div class="col-md-6">
                <label for="user_nickname" class="form-label">Tu Alias / Nickname <span
                    class="text-danger">*</span></label>
                <input type="text" id="user_nickname" name="user_nickname" class="form-control"
                  value="<?= $user->user_nickname ?>" required>
              </div>
              <div class="col-md-6">
                <label for="user_display_name" class="form-label">¿Cómo quieres que te veamos?</label>
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
          <a href="<?= front_route("account/profile") ?>"
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
</div>
