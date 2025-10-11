<?php $theme->blockStart("script"); ?>
<script src="<?= SITE_URL ?>/static/scripts/test-mail.js"></script>
<?php $theme->blockEnd("script"); ?>

<div class="card">
  <div class="card-body">
    <form action="" method="post">
      <div class="mb-3">
        <label class="form-label" for="">Host</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["smtp_host"] ?>" name="st_smtphost">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Email</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["smtp_email"] ?>" name="st_smtpemail">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Password</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["smtp_password"] ?>" name="st_smtppassword">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Port</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["smtp_port"] ?>" name="st_smtpport">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Encrypt</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["smtp_encryption"] ?>" name="st_smtpencrypt">
      </div>

      <hr>
      <button class="btn btn-primary" type="submit">Guardar</button>
      <button id="testMail" class="btn btn-info">
        <i id="loading" class="fa fa-spinner fa-spin" style="display:none"></i>

        <!-- <div class="spinner-border text-light" role="status">
          <span class="visually-hidden">Loading...</span>
        </div> -->
        Enviar Correo de Prueba
      </button>
    </form>

  </div>
</div>