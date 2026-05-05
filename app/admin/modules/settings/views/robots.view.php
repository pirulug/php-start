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

<form method="POST">
  <div class="card mb-3">
    <div class="card-body">
      <div class="mb-3">
        <label for="content" class="form-label">Contenido</label>
        <textarea class="form-control" id="content" name="content"
          style="field-sizing: content;min-height: 3lh;"><?php echo $file_content; ?></textarea>
      </div>
    </div>
  </div>

  <div class="bg-body p-3 rounded d-flex justify-content-end gap-2 sticky-bottom">
    <button type="submit" class="btn btn-primary px-5 text-uppercase small fw-bold">
      <i class="fa-solid fa-floppy-disk me-2"></i>
      Guardar Cambios
    </button>
  </div>
</form>