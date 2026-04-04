<?php start_block('title'); ?>
Archivo Robots.txt
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Robots']
]) ?>
<?php end_block(); ?>

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