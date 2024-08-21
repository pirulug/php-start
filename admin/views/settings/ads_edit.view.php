<?php require BASE_DIR . "/admin/views/partials/top.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/navbar.partial.php"; ?>



<div class="card">
  <div class="card-body">
    <form enctype="multipart/form-data" action="" method="post">

      <p class="mb-3">
        <?= $ad->title ?>
        <small>
          <?= $ad->subtitle ?>
        </small>
      </p>

      <input type="hidden" value="<?= $ad->id ?>" name="id">
      <div class="mb-3">
        <textarea class="mceNoEditor form-control" type="text" name="content"
          style="field-sizing: content;min-height: 4lh;" required><?= $ad->content ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>

        <select class="custom-select form-control" name="status" required="">
          <option value="0" <?= $ad->status == "0" ? "selected" : "" ?>>Draft</option>
          <option value="1" <?= $ad->status == "1" ? "selected" : "" ?>>Publish</option>
        </select>
      </div>

      <hr>
      <button class="btn btn-primary" type="submit" name="save">Guardar</button>

    </form>
  </div>
</div>

<?php require BASE_DIR . "/admin/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/bottom.partial.php"; ?>