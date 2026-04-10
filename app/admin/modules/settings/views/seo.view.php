<?php start_block('title'); ?>
SEO & Tracking
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'SEO & Tracking']
]) ?>
<?php end_block(); ?>

<form action="" method="POST">
  <div class="row">
    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-body">
          <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-chart-line me-2"></i>Google Services</h6>
          
          <div class="mb-4">
            <label class="form-label fw-bold">Google Analytics (ID de Medida)</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-brands fa-google text-primary"></i></span>
                <input type="text" name="ga_id" class="form-control" value="<?= $options->google_analytics_id ?? '' ?>" placeholder="Ej: G-XXXXXXXXXX">
            </div>
            <div class="form-text">Id de medicion para Google Analytics 4.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Google Search Console</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="gsc_id" class="form-control" value="<?= $options->google_search_console ?? '' ?>" placeholder="Codigo de verificacion">
            </div>
            <div class="form-text">Etiqueta meta de verificacion de propiedad.</div>
          </div>

          <hr class="my-4">

          <h6 class="text-primary fw-bold mb-3"><i class="fa-brands fa-facebook me-2"></i>Meta Ads</h6>
          
          <div class="mb-3">
            <label class="form-label fw-bold">Meta Pixel ID</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-brands fa-facebook-f text-primary"></i></span>
                <input type="text" name="meta_id" class="form-control" value="<?= $options->meta_pixel_id ?? '' ?>" placeholder="Ej: 123456789012345">
            </div>
            <div class="form-text">ID del Pixel de Facebook/Meta para seguimiento de conversiones.</div>
          </div>

        </div>
        <div class="card-footer d-flex justify-content-end py-3">
          <button class="btn btn-primary px-4 fw-bold text-uppercase small" type="submit">
            <i class="fa-solid fa-save me-1"></i> Guardar Tracking
          </button>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="text-info fw-bold mb-3"><i class="fa-solid fa-lightbulb me-2"></i>Sobre Tracking</h6>
                <p class="small opacity-75 mb-0">
                    Estos codigos se insertaran automaticamente en la seccion <code>&lt;head&gt;</code> de tu sitio web si el plugin esta configurado. Asegurate de ingresar solo los IDs, no el script completo.
                </p>
            </div>
        </div>
    </div>
  </div>
</form>
