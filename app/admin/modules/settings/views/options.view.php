<?php start_block("title"); ?>
Opciones General
<?php end_block(); ?>

<?php start_block("css"); ?>

<?php end_block(); ?>

<?php start_block("js"); ?>

<?php end_block(); ?>

<form action="" method="post">
  <div class="card mb-3">
    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <div class="">
            <label for="loader" class="form-label">Loader</label>
            <select name="loader" id="loader" class="form-select">
              <option value="">- Seleccionar -</option>
              <option value="true" <?= $optionsRaw["loader"] ? "" : "selected" ?>>Mostras</option>
              <option value="false" <?= $optionsRaw["loader"] ? "selected" : "" ?>>No Mostras</option>
            </select>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="bg-body p-3 rounded text-end">
    <button class="btn btn-primary">Guardar</button>
  </div>
</form>