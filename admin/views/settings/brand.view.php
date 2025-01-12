<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

<div class="card">
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <table class="table align-middle">
        <tbody>
          <tr>
            <td class="w-50">
              <label class="form-label" for="">FAVICON</label>
              <p>Recommended Size: <b>128 x 128 Pixels</b></p>
              <input class="form-control" type="hidden" value='<?= $brand->st_favicon ?>' name="st_favicon_save">
              <input class="form-control" type="file" name="st_favicon">
            </td>
            <td class="w-50">
              <img src="<?= SITE_URL . "/uploads/site/favicons/" . $st_favicon->favicon ?>" alt="favicon" height="128">
            </td>
          </tr>
          <tr>
            <td class="w-50">
              <label class="form-label" for="">WHITE LOGO</label>
              <p>Recommended Size: <b>320 x 71 Pixels</b></p>
              <input class="form-control" type="hidden" value="<?= $brand->st_whitelogo ?>" name="st_whitelogo_save">
              <input class="form-control" type="file" name="st_whitelogo">
            </td>
            <td class="w-50 bg-dark">
              <img src="<?= SITE_URL . "/uploads/site/" . $brand->st_whitelogo ?>" alt="whitelogo" height="71">
            </td>
          </tr>
          <tr>
            <td class="w-50">
              <label class="form-label" for="">DARK LOGO</label>
              <p>Recommended Size: <b>320 x 71 Pixels</b></p>
              <input class="form-control" type="hidden" value="<?= $brand->st_darklogo ?>" name="st_darklogo_save">
              <input class="form-control" type="file" name="st_darklogo">
            </td>
            <td class="w-50 bg-white">
              <img src="<?= SITE_URL . "/uploads/site/" . $brand->st_darklogo ?>" alt="darklogo"
              height="71">
            </td>
          </tr>
        </tbody>
      </table>

      <hr>

      <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>