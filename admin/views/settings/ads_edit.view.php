<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

<div class="card">
  <div class="card-body">
    <form enctype="multipart/form-data" action="" method="post">

      <p class="mb-3">
        <?= $ad->ad_title ?>
        <small>
          <?= $ad->ad_subtitle ?>
        </small>
      </p>

      <input type="hidden" value="<?= $ad->ad_id ?>" name="id">
      <div class="mb-3">
        <textarea class="mceNoEditor form-control" type="text" name="content"
          style="field-sizing: content;min-height: 4lh;" required><?= $ad->ad_content ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>

        <select class="custom-select form-control" name="status" required="">
          <option value="0" <?= $ad->ad_status == "0" ? "selected" : "" ?>>Draft</option>
          <option value="1" <?= $ad->ad_status == "1" ? "selected" : "" ?>>Publish</option>
        </select>
      </div>

      <hr>
      <button class="btn btn-primary" type="submit" name="save">Guardar</button>

    </form>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>