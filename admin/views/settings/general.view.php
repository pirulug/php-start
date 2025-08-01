<?php $theme->blockStart("style"); ?>
<link rel="stylesheet" href="<?= $url_static->css("tagify.css") ?>">
<?php $theme->blockEnd("style"); ?>

<?php $theme->blockStart("script"); ?>
<script src="<?= $url_static->js("tagify.js") ?>"></script>
<script>
  const input = document.getElementById('tag-input');
  new Tagify(input);
</script>
<?php $theme->blockEnd("script"); ?>

<?php require BASE_DIR_ADMIN . "/views/_partials/top.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/navbar.partial.php"; ?>

<div class="card">
  <div class="card-body">
    <form action="" method="POST" enctype="multipart/form-data">
      <h3 class="h5 m-0">General</h3>
      <hr>
      <div class="mb-3">
        <label class="form-label">Site Name</label>
        <input class="form-control" type="text" value="<?= $optionsRaw['site_name'] ?? '' ?>" name="st_sitename">
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="st_description"
          style="field-sizing: content;min-height: 3lh;"><?= $optionsRaw['site_description'] ?? '' ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Keywords</label>
        <input class="form-control" id="tag-input" type="text" value='<?= $optionsRaw['site_keywords'] ?>'
          name="st_keywords">
      </div>
      <h3 class="h5 m-0">Social</h3>
      <hr>
      <div class="mb-3">
        <label class="form-label">Facebook</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["facebook"] ?>" name="st_facebook">
      </div>
      <div class="mb-3">
        <label class="form-label">Twitter</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["twitter"] ?>" name="st_twitter">
      </div>
      <div class="mb-3">
        <label class="form-label">Instagram</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["instagram"] ?>" name="st_instagram">
      </div>
      <div class="mb-3">
        <label class="form-label">Youtube</label>
        <input class="form-control" type="text" value="<?= $optionsRaw["youtube"] ?>" name="st_youtube">
      </div>

      <hr>
      <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
  </div>
</div>

<?php require BASE_DIR_ADMIN . "/views/_partials/footer.partial.php"; ?>
<?php require BASE_DIR_ADMIN . "/views/_partials/bottom.partial.php"; ?>