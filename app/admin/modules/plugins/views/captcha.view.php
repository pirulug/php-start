<?php start_block("title"); ?>
Captchas
<?php end_block(); ?>

<div class="card mt-3">
  <div class="card-body">
    <form method="post">
      <h5>Formulario</h5>

      <div class="mb-3">
        <label class="form-label">Texto</label>
        <input class="form-control" type="text" name="texto" required>
      </div>

      <?= $captcha->render(); ?>

      <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
  </div>
</div>