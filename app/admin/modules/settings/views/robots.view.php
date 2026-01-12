<?php start_block("title") ?>
Robots.txt
<?php end_block() ?>

<div class="card">
  <div class="card-body">

    <form method="POST">
      <div class="mb-3">
        <label for="content" class="form-label">Contenido</label>
        <textarea class="form-control" id="content" name="content"
          style="field-sizing: content;min-height: 3lh;"><?php echo $file_content; ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
  </div>
</div>