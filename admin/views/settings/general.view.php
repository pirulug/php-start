<?php blockStart("style"); // Block ?>
<link rel="stylesheet" href="<?= SITE_URL ?>/admin/assets/css/tagify.css">
<?php blockEnd("style"); ?>

<?php blockStart("script"); // Block ?>
<script src="<?= SITE_URL ?>/admin/assets/js/tagify.js"></script>
<script>
  const input = document.getElementById('tag-input');
  new Tagify(input);
</script>
<?php blockEnd("script"); ?>

<?php require BASE_DIR . "/admin/views/partials/top.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/navbar.partial.php"; ?>



<div class="card">
  <div class="card-body">
    <form action="" method="POST">
      <h3 class="h5 m-0">General</h3>
      <hr>
      <div class="mb-3">
        <label class="form-label">Site Name</label>
        <input class="form-control" type="text" value="<?= $settings->st_sitename ?>" name="st_sitename">
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="st_description"
          style="field-sizing: content;min-height: 3lh;"><?= $settings->st_description ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Keywords</label>
        <input class="form-control" id="tag-input" type="text" value='<?= $settings->st_keywords ?>' name="st_keywords">
      </div>
      <h3 class="h5 m-0">Social</h3>
      <hr>
      <div class="mb-3">
        <label class="form-label">Facebook</label>
        <input class="form-control" type="text" value="<?= $settings->st_facebook ?>" name="st_facebook">
      </div>
      <div class="mb-3">
        <label class="form-label">Twitter</label>
        <input class="form-control" type="text" value="<?= $settings->st_twitter ?>" name="st_twitter">
      </div>
      <div class="mb-3">
        <label class="form-label">Instagram</label>
        <input class="form-control" type="text" value="<?= $settings->st_instagram ?>" name="st_instagram">
      </div>
      <div class="mb-3">
        <label class="form-label">Youtube</label>
        <input class="form-control" type="text" value="<?= $settings->st_youtube ?>" name="st_youtube">
      </div>

      <hr>
      <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
  </div>
</div>

<?php require BASE_DIR . "/admin/views/partials/footer.partial.php"; ?>
<?php require BASE_DIR . "/admin/views/partials/bottom.partial.php"; ?>