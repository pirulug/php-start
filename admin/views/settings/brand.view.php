<?php require BASE_DIR . "/admin/views/partials/top.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/navbar.partial.php"; ?>



<div class="card">
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <table class="table align-middle">
        <tbody>
          <tr>
            <td class="w-50">
              <label class="form-label" for="">FAVICON</label>
              <p>Recommended Size: <b>128 x 128 Pixels</b></p>
              <input class="form-control" type="hidden" value="<?= $brand->st_favicon ?>" name="st_favicon_save">
              <input class="form-control" type="file" name="st_favicon">
            </td>
            <td class="w-50">
              <img src="<?= $brand->st_favicon ?? "https://dummyimage.com/128x128/000/fff.jpg" ?>" alt="favicon"
                height="128">
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
              <img src="<?= $brand->st_whitelogo ?? "https://dummyimage.com/320x71/000/fff.jpg" ?>" alt="whitelogo"
                height="71">
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
              <img src="<?= $brand->st_darklogo ?? "https://dummyimage.com/320x71/fff/000.jpg" ?>" alt="darklogo"
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

<?php require BASE_DIR . "/admin/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/bottom.partial.php"; ?>