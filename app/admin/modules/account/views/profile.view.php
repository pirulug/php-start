<?php start_block('title'); ?>
Mi Perfil
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Cuenta']
]) ?>
<?php end_block(); ?>

<div class="row g-3">

  <!-- SIDEBAR DE NAVEGACIÓN -->
  <div class="col-md-4 col-lg-3">
    <?php require_once BASE_DIR . '/app/admin/modules/account/views/partials/sidebar.php'; ?>
  </div>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="col-md-8 col-lg-9">

    <!-- TARJETA RESUMEN -->
    <div class="card mb-3" style="background: linear-gradient(135deg, var(--pr-primary) 0%, #6610f2 100%);">
      <div class="card-body p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative">
            <img src="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>"
              class="border border-3 border-white border-opacity-25"
              style="width: 90px; height: 90px; object-fit: cover; border-radius: 18px;"
              alt="<?= $user->user_display_name ?>">
            <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle"
              style="width: 16px; height: 16px;" title="En línea"></span>
          </div>
          <div class="text-white">
            <h4 class="mb-1 fw-bold"><?= $user->user_display_name ?></h4>
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 text-uppercase fw-bold"
                style="font-size: 0.65rem;">
                <?= $user->role_name ?>
              </span>
              <span class="text-white-50 small">
                <i class="fa-solid fa-at me-1"></i><?= $user->user_login ?>
              </span>
            </div>
            <a href="<?= admin_route("account/settings/profile") ?>"
              class="btn btn-light btn-sm px-3 text-uppercase small fw-bold">
              <i class="fa-solid fa-pencil me-1"></i> Editar Perfil
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- INFORMACIÓN DETALLADA -->
    <div class="card mb-3">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="card-title mb-0 fw-bold text-uppercase">
          <i class="fa-solid fa-circle-info me-2 text-primary"></i>Datos de la Cuenta
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <tbody>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold" style="width: 200px;">Correo
                  Electrónico</td>
                <td class="pe-3 py-3 fw-medium text-body"><?= $user->user_email ?></td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Nombre de Usuario</td>
                <td class="pe-3 py-3 fw-medium text-body"><?= $user->user_login ?></td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Rol / Permisos</td>
                <td class="pe-3 py-3">
                  <span class="badge bg-primary-subtle text-primary text-uppercase fw-bold"
                    style="font-size: 0.7rem;"><?= $user->role_name ?></span>
                </td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Último Acceso</td>
                <td class="pe-3 py-3 small text-body-secondary">
                  <i class="fa-regular fa-clock me-1"></i><?= format_datetime($user->user_last_login) ?>
                </td>
              </tr>
              <tr>
                <td class="ps-3 py-3 text-body-secondary small text-uppercase fw-bold">Fecha de Registro</td>
                <td class="pe-3 py-3 small text-body-secondary">
                  <i class="fa-regular fa-calendar me-1"></i><?= format_datetime($user->user_created) ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- BLOQUE DEBUG -->
    <div class="card border-warning-subtle">
      <div class="card-header bg-warning-subtle border-bottom border-warning-subtle py-2">
        <h6 class="card-title mb-0 text-warning-emphasis small text-uppercase fw-bold">
          <i class="fa-solid fa-terminal me-2"></i>
          Permisos del Usuario (Debug)
        </h6>
      </div>
      <div class="card-body">
      </div>
    </div>

  </div>
</div>