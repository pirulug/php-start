<?php start_block('title'); ?>
Redes Sociales
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Redes Sociales']
]) ?>
<?php end_block(); ?>

<form action="" method="POST">
  <div class="card">
    <div class="card-body">
      <div class="row g-4">
        
        <div class="col-md-6">
          <label class="form-label fw-bold">Facebook</label>
          <div class="input-group mb-3">
            <span class="input-group-text border-end-0"><i class="fa-brands fa-facebook text-primary fs-5"></i></span>
            <input type="text" name="st_facebook" class="form-control" value="<?= $options->facebook ?? '' ?>" placeholder="https://facebook.com/tu-pagina">
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">Twitter / X</label>
          <div class="input-group mb-3">
            <span class="input-group-text border-end-0"><i class="fa-brands fa-x-twitter fs-5"></i></span>
            <input type="text" name="st_twitter" class="form-control" value="<?= $options->twitter ?? '' ?>" placeholder="https://x.com/tu-perfil">
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">Instagram</label>
          <div class="input-group mb-3">
            <span class="input-group-text border-end-0"><i class="fa-brands fa-instagram text-danger fs-5"></i></span>
            <input type="text" name="st_instagram" class="form-control" value="<?= $options->instagram ?? '' ?>" placeholder="https://instagram.com/tu-perfil">
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">YouTube</label>
          <div class="input-group mb-3">
            <span class="input-group-text border-end-0"><i class="fa-brands fa-youtube text-danger fs-5"></i></span>
            <input type="text" name="st_youtube" class="form-control" value="<?= $options->youtube ?? '' ?>" placeholder="https://youtube.com/c/tu-canal">
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">LinkedIn</label>
          <div class="input-group mb-3">
            <span class="input-group-text border-end-0"><i class="fa-brands fa-linkedin text-primary fs-5"></i></span>
            <input type="text" name="st_linkedin" class="form-control" value="<?= $options->linkedin ?? '' ?>" placeholder="https://linkedin.com/company/tu-empresa">
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">TikTok</label>
          <div class="input-group mb-3">
            <span class="input-group-text border-end-0"><i class="fa-brands fa-tiktok fs-5"></i></span>
            <input type="text" name="st_tiktok" class="form-control" value="<?= $options->tiktok ?? '' ?>" placeholder="https://tiktok.com/@tu-usuario">
          </div>
        </div>

      </div>
    </div>
    <div class="card-footer d-flex justify-content-end py-3">
      <button class="btn btn-primary px-4 fw-bold text-uppercase small" type="submit">
        <i class="fa-solid fa-save me-1"></i> Guardar Redes
      </button>
    </div>
  </div>
</form>
