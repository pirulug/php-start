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

<link rel="stylesheet" href="<?= APP_URL . "/static/plugins/dropzone/dropimg.css" ?>">

<div class="row g-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <div class="card sticky-top" style="top: 1rem; z-index: 1;">
      <div class="card-header bg-transparent border-bottom p-3">
        <h6 class="m-0 fw-bold text-uppercase small text-body-secondary">Gestión de Cuenta</h6>
      </div>
      <div class="list-group list-group-flush">
        <a href="<?= admin_route("account/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-id-card fa-fw"></i>
          <span class="fs-7">Vista General</span>
        </a>
        <a href="<?= admin_route("account/settings/profile") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 <?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false ? 'active' : '' ?>">
          <i class="fa-solid fa-user-circle fa-fw text-primary"></i>
          <span class="fw-bold fs-7">Información del Perfil</span>
        </a>
        <a href="<?= admin_route("account/settings/password") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-shield-halved fa-fw"></i>
          <span class="fs-7">Seguridad y Contraseña</span>
        </a>
        <a href="<?= admin_route("account/settings/api") ?>" 
           class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3">
          <i class="fa-solid fa-key fa-fw"></i>
          <span class="fs-7">API Keys</span>
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">
    <form enctype="multipart/form-data" method="POST">
      <div class="card mb-3">
        <div class="card-header bg-transparent border-bottom py-3">
          <h5 class="card-title mb-0 fs-6 fw-bold text-uppercase">
            <i class="fa-solid fa-user-edit me-2 text-primary"></i>Actualizar Perfil
          </h5>
        </div>

        <div class="card-body p-3">
          
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
              <div class="mt-2 text-body-secondary x-small">Formatos permitidos: JPG, PNG, WEBP.</div>
            </div>
          </div>

          <!-- SECCIÓN: IDENTIDAD -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Nombre de Usuario</label>
              <div class="input-group">
                <span class="input-group-text bg-body border-end-0"><i class="fa-solid fa-at text-body-secondary"></i></span>
                <input type="text" class="form-control bg-body border-start-0 fw-medium" value="<?= $user->user_login ?>" disabled readonly>
              </div>
              <div class="form-text x-small mt-2">El nombre de usuario es un identificador único del sistema.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Correo Electrónico <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" name="user_email" class="form-control border-start-0" value="<?= $user->user_email ?>" required>
              </div>
            </div>
          </div>

          <!-- SECCIÓN: NOMBRES -->
          <div class="bg-body p-3 border rounded mb-3">
             <div class="row g-3">
                <div class="col-md-12">
                  <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Nombres Protagonistas</label>
                  <input type="text" name="user_first_name" class="form-control" value="<?= $usermeta->first_name ?? "" ?>" placeholder="Tu nombre real">
                </div>
                <div class="col-md-6">
                  <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Primer Apellido</label>
                  <input type="text" name="user_last_name" class="form-control" value="<?= $usermeta->last_name ?? "" ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Segundo Apellido</label>
                  <input type="text" name="user_second_last_name" class="form-control" value="<?= $usermeta->second_last_name ?? "" ?>">
                </div>
             </div>
          </div>

          <!-- SECCIÓN: VISIBILIDAD -->
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Alias / Nickname <span class="text-danger">*</span></label>
              <input type="text" name="user_nickname" class="form-control" value="<?= $user->user_nickname ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-uppercase x-small fw-bold text-body-secondary">Mostrar públicamente como</label>
              <select name="user_display_name" class="form-select fw-bold text-primary">
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

        <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
          <button name="update_profile" type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
            <i class="fa-solid fa-save me-2"></i> Guardar Cambios
          </button>
        </div>
      </div>
    </form>
  </div>

</div>

<script src="<?= APP_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();
</script>

<style>
  .fs-7 { font-size: 0.875rem; }
  .x-small { font-size: 0.75rem; }
</style>
