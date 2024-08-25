<?php blockStart("script"); ?>
<script>
  document.getElementById('testMail').addEventListener('click', function (event) {
    event.preventDefault();

    const button = event.target;
    const spinner = document.getElementById('spinner');

    // Deshabilitar el botón y mostrar el spinner
    button.setAttribute('disabled', true);
    spinner.style.display = 'inline-block';

    fetch('test-mail.php', {
      method: 'GET',
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta de la red');
        }
        return response.text();
      })
      .then(data => {
        console.log('Correo de prueba enviado:', data);
        alert('Correo de prueba enviado con éxito');
      })
      .catch(error => {
        console.error('Hubo un problema con la solicitud fetch:', error);
        alert('Error al enviar el correo de prueba');
      })
      .finally(() => {
        button.removeAttribute('disabled');
        spinner.style.display = 'none';
      });
  });
</script>
<?php blockEnd("script"); ?>

<?php require BASE_DIR . "/admin/views/partials/top.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/navbar.partial.php"; ?>

<div class="card">
  <div class="card-body">
    <form action="" method="post">
      <div class="mb-3">
        <label class="form-label" for="">Host</label>
        <input class="form-control" type="text" value="<?= $smtp->st_smtphost ?>" name="st_smtphost">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Email</label>
        <input class="form-control" type="text" value="<?= $smtp->st_smtpemail ?>" name="st_smtpemail">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Password</label>
        <input class="form-control" type="text" value="<?= $smtp->st_smtppassword ?>" name="st_smtppassword">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Port</label>
        <input class="form-control" type="text" value="<?= $smtp->st_smtpport ?>" name="st_smtpport">
      </div>
      <div class="mb-3">
        <label class="form-label" for="">Encrypt</label>
        <input class="form-control" type="text" value="<?= $smtp->st_smtpencrypt ?>" name="st_smtpencrypt">
      </div>

      <hr>
      <button class="btn btn-primary" type="submit">Guardar</button>
      <button id="testMail" class="btn btn-success">
        <i id="spinner" class="fa fa-spinner fa-spin" style="display: none;"></i>
        Enviar Correo de Prueba
      </button>
    </form>

  </div>
</div>

<?php require BASE_DIR . "/admin/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/bottom.partial.php"; ?>