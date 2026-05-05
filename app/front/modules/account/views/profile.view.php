<?php start_block('title'); ?>
Mi Cuenta
<?php end_block(); ?>

<div class="container my-3">
  <div class="row g-3">

    <!-- SIDEBAR DE NAVEGACIÓN -->
    <div class="col-md-4 col-lg-3">
      <?php require_once BASE_DIR . '/app/front/modules/account/views/partials/sidebar.php'; ?>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="col-md-8 col-lg-9">

      <!-- HEADER DE PERFIL -->
      <div class="card mb-3 overflow-hidden"
        style="background: linear-gradient(135deg, var(--pr-primary) 0%, #6610f2 100%); border: none;">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
            <div class="position-relative">
              <img alt="<?= $user->user_display_name ?>"
                src="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>"
                class="border border-3 border-white border-opacity-25"
                style="width: 100px; height: 100px; object-fit: cover; border-radius: 20px;">
              <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle"
                style="width: 18px; height: 18px;" title="Activo ahora"></span>
            </div>
            <div class="text-white">
              <h3 class="fw-bold mb-1"><?= $user->user_display_name ?></h3>
              <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-3">
                <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 text-uppercase fw-bold"
                  style="font-size: 0.65rem;">
                  <?= $user->role_name ?>
                </span>
                <span class="text-white-50 small">
                  <i class="fa-solid fa-at me-1"></i><?= $user->user_login ?>
                </span>
              </div>
              <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
                <a href="<?= front_route("account/settings/profile") ?>"
                  class="btn btn-light btn-sm px-4 text-uppercase small fw-bold">
                  <i class="fa-solid fa-user-gear me-2"></i> Configurar Perfil
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TABLA DE INFORMACIÓN -->
      <div class="card mb-3">
        <div class="card-header py-3 ps-4">
          <h6 class="card-title mb-0 fw-bold text-uppercase">
            <i class="fa-solid fa-circle-info me-2 text-primary"></i>Resumen de la Cuenta
          </h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <tbody>
                <tr>
                  <td class="ps-4 py-3 text-body-secondary text-uppercase fw-bold small" style="width: 220px;">Email de
                    contacto</td>
                  <td class="pe-4 py-3 fw-medium text-body"><?= $user->user_email ?></td>
                </tr>
                <tr>
                  <td class="ps-4 py-3 text-body-secondary text-uppercase fw-bold small">Nombre registrado</td>
                  <td class="pe-4 py-3 fw-medium text-body">
                    <?= trim(($usermeta->first_name ?? '') . ' ' . ($usermeta->last_name ?? '') . ' ' . ($usermeta->second_last_name ?? '')) ?: 'Sin registrar' ?>
                  </td>
                </tr>
                <tr>
                  <td class="ps-4 py-3 text-body-secondary text-uppercase fw-bold small">Username</td>
                  <td class="pe-4 py-3 text-body"><?= $user->user_login ?></td>
                </tr>
                <tr>
                  <td class="ps-4 py-3 text-body-secondary text-uppercase fw-bold small">Rango / Nivel</td>
                  <td class="pe-4 py-3">
                    <span class="badge bg-primary-subtle text-primary text-uppercase fw-bold"
                      style="font-size: 0.7rem;"><?= $user->role_name ?></span>
                  </td>
                </tr>
                <tr>
                  <td class="ps-4 py-3 text-body-secondary text-uppercase fw-bold small">Miembro desde</td>
                  <td class="pe-4 py-3 small text-body-secondary">
                    <i class="fa-regular fa-calendar-check me-1"></i><?= format_date($user->user_created) ?>
                  </td>
                </tr>
                <tr>
                  <td class="ps-4 py-3 text-body-secondary text-uppercase fw-bold small">Actividad reciente</td>
                  <td class="pe-4 py-3 small text-body-secondary">
                    <i class="fa-regular fa-clock me-1"></i><?= format_datetime($user->user_last_login) ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>