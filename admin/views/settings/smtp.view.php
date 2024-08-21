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
    </form>
  </div>
</div>

<?php require BASE_DIR . "/admin/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/bottom.partial.php"; ?>