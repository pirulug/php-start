<?php start_block('title'); ?>
Ajustes SMTP
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Correo (SMTP)']
]) ?>
<?php end_block(); ?>

<?php
$smtp = $config->smtp();
?>
<?php start_block("js") ?>
<?= static_assets_js("mail.js") ?>
<?= url_script_admin('settings', 'smtp') ?>
<?php end_block() ?>

<form action="" method="POST">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-paper-plane"></i>
            Servidor de Correo
          </h6>

          <div class="row g-3">
            <div class="col-md-9">
              <div class="mb-0">
                <label for="st_smtphost" class="form-label">Servidor SMTP <span class="text-danger">*</span></label>
                <input type="text" id="st_smtphost" name="st_smtphost" class="form-control"
                  value="<?= htmlspecialchars($smtp->host) ?>" placeholder="Ej: mail.example.com" required>
                <div class="invalid-feedback">Ingrese el host del servidor.</div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="mb-0">
                <label for="st_smtpport" class="form-label">Puerto <span class="text-danger">*</span></label>
                <input type="number" id="st_smtpport" name="st_smtpport" class="form-control"
                  value="<?= htmlspecialchars($smtp->port) ?>" placeholder="Ej: 587" required>
                <div class="invalid-feedback">Ingrese el puerto.</div>
              </div>
            </div>

            <div class="col-12">
              <div class="mb-0">
                <label for="st_smtpemail" class="form-label">Usuario / Correo <span class="text-danger">*</span></label>
                <input type="email" id="st_smtpemail" name="st_smtpemail" class="form-control"
                  value="<?= htmlspecialchars($smtp->email) ?>" placeholder="no-reply@example.com" required>
                <div class="invalid-feedback">Ingrese un correo válido.</div>
              </div>
            </div>

            <div class="col-12">
              <div class="mb-0">
                <label for="st_smtppassword" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" id="st_smtppassword" name="st_smtppassword" class="form-control"
                    value="<?= htmlspecialchars($smtp->password) ?>" placeholder="••••••••" required>
                  <button class="btn btn-outline-secondary" type="button" data-pr-toggle-password=""
                    title="Mostrar/Ocultar">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
                <div class="invalid-feedback">Ingrese la contraseña.</div>
              </div>
            </div>

            <div class="col-12">
              <div class="mb-0">
                <label for="st_smtpencrypt" class="form-label">Cifrado</label>
                <select id="st_smtpencrypt" name="st_smtpencrypt" class="form-select">
                  <option value="none" <?= $smtp->encryption === 'none' ? 'selected' : '' ?>>Ninguno (None)</option>
                  <option value="ssl" <?= $smtp->encryption === 'ssl' ? 'selected' : '' ?>>SSL</option>
                  <option value="tls" <?= $smtp->encryption === 'tls' ? 'selected' : '' ?>>TLS</option>
                </select>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Botonera Pegajosa -->
      <div class="bg-body p-3 rounded d-flex justify-content-between align-items-center gap-2 sticky-bottom mt-3">
        <button id="testMail" type="button"
          class="btn btn-outline-secondary px-4 text-uppercase small fw-bold d-flex align-items-center gap-2">
          <span id="loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          <i id="iconSend" class="fa-solid fa-paper-plane"></i>
          <span id="btnText">Probar Conexión</span>
        </button>

        <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
          <i class="fa-solid fa-floppy-disk me-2"></i>
          Guardar Cambios
        </button>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-info"></i>
            Información
          </h6>
          <p class="text-body small mb-0">
            Configure las credenciales de su servidor de correo para que el sistema pueda enviar notificaciones,
            recuperaciones de contraseña y otros correos electrónicos automáticos.
          </p>
          <hr class="my-3 opacity-10">
          <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center gap-2 small">
              <i class="fa-solid fa-circle-check text-success"></i>
              <span class="text-body">Puerto 587 recomendado para TLS</span>
            </div>
            <div class="d-flex align-items-center gap-2 small">
              <i class="fa-solid fa-circle-check text-success"></i>
              <span class="text-body">Puerto 465 recomendado para SSL</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>