<div class="card">
  <div class="card-header bg-transparent py-3">
    <h5 class="card-title mb-0 d-flex align-items-center gap-2">
      <i class="fa-solid fa-server text-primary"></i>
      Configuración SMTP
    </h5>
  </div>

  <div class="card-body p-4">
    <form action="" method="post" autocomplete="off">

      <div class="row g-3 mb-3">
        <div class="col-md-8">
          <label class="form-label">Servidor Host</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-globe"></i></span>
            <input class="form-control" type="text" placeholder="smtp.ejemplo.com"
              value="<?= $optionsRaw["smtp_host"] ?>" name="st_smtphost">
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Puerto</label>
          <div class="input-group">
            <span class="input-group-text">#</span>
            <input class="form-control" type="number" placeholder="587" value="<?= $optionsRaw["smtp_port"] ?>"
              name="st_smtpport">
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Correo Electrónico</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
          <input class="form-control" type="email" placeholder="nombre@dominio.com"
            value="<?= $optionsRaw["smtp_email"] ?>" name="st_smtpemail">
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label">Contraseña</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
            <input class="form-control" type="password" id="smtpPass" value="<?= $optionsRaw["smtp_password"] ?>"
              name="st_smtppassword">
            <button class="btn btn-outline-secondary" type="button" onclick="togglePass()">
              <i class="fa-solid fa-eye" id="toggleIcon"></i>
            </button>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Tipo de Encriptación</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <select class="form-select" name="st_smtpencrypt">
              <option value="tls" <?= $optionsRaw["smtp_encryption"] == 'tls' ? 'selected' : '' ?>>TLS (Recomendado)
              </option>
              <option value="ssl" <?= $optionsRaw["smtp_encryption"] == 'ssl' ? 'selected' : '' ?>>SSL</option>
              <option value="" <?= empty($optionsRaw["smtp_encryption"]) ? 'selected' : '' ?>>Ninguna</option>
            </select>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center pt-3">
        <button id="testMail" type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2">
          <i id="loading" class="fa fa-spinner fa-spin d-none"></i>
          <i class="fa-solid fa-paper-plane"></i>
          <span>Probar Conexión</span>
        </button>

        <button class="btn btn-primary px-4" type="submit">
          <i class="fa-solid fa-floppy-disk me-2"></i>Guardar
        </button>
      </div>

    </form>
  </div>
</div>


<script>
  function togglePass() {
    const input = document.getElementById('smtpPass');
    const icon = document.getElementById('toggleIcon');
    if (input.type === "password") {
      input.type = "text";
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      input.type = "password";
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }

  // Lógica visual para el botón de carga (sin CSS extra, usando d-none de bootstrap)
  document.getElementById('testMail').addEventListener('click', function () {
    const iconLoad = document.getElementById('loading');
    // Ejemplo visual:
    // iconLoad.classList.remove('d-none');
  });
</script>

<script src="<?= APP_URL ?>/static/scripts/test-mail.js"></script>