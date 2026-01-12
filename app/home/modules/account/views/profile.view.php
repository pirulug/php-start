<?php start_block('title'); ?>
Profile
<?php end_block(); ?>

<div class="container my-4">
  <div class="row g-4">
    <div class="col-md-4 col-lg-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex flex-column align-items-center text-center">
            <div class="mb-3">
              <img class="img-fluid" style="max-width: 150px; height: auto;"
                src="<?= APP_URL ?>/storage/uploads/user/<?= $user->user_image ?>" alt="Profile Image">
            </div>

            <h4 class="card-title mb-1"><?= $user->user_display_name ?></h4>
            <span class="badge bg-primary bg-opacity-10 text-primary mb-4">
              <?= $user->role_name ?>
            </span>
          </div>

          <div class="mt-3">
            <div class="d-flex justify-content-between py-2">
              <span class="fw-medium">Usuario</span>
              <span class="text-end"><?= $user->user_login ?></span>
            </div>

            <div class="d-flex justify-content-between py-2">
              <span class="fw-medium">Último Acceso</span>
              <span class="text-end small"><?= $user->user_last_login ?></span>
            </div>

            <div class="d-flex justify-content-between py-2">
              <span class="fw-medium">Miembro desde</span>
              <span class="text-end small"><?= $user->user_created ?></span>
            </div>
          </div>

          <div class="d-grid mt-4">
            <a href="<?= APP_URL ?>/profile/edit" class="btn btn-primary">
              <i class="fa fa-pencil me-2"></i> Editar Perfil
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8 col-lg-9">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title mb-4 text-primary">
            Información Personal
          </h5>

          <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle">
              <tbody>
                <tr>
                  <td class="fw-bold" width="180">Display Name</td>
                  <td class="fs-5"><?= $user->user_display_name ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Nombres</td>
                  <td><?= $usermeta->first_name ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Primer Apellido</td>
                  <td><?= $usermeta->last_name ?? "-" ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Segundo Apellido</td>
                  <td><?= $usermeta->second_last_name ?? "-" ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Username</td>
                  <td><?= $user->user_login ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Email</td>
                  <td><?= $user->user_email ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Rol</td>
                  <td><?= $user->role_name ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Último Acceso</td>
                  <td><?= $user->user_last_login ?></td>
                </tr>
                <tr>
                  <td class="fw-bold">Miembro desde</td>
                  <td>
                    <?= $user->user_created ?>
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