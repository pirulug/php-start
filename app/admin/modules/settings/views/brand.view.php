<?php start_block("title"); ?>
Identidad Visual
<?php end_block(); ?>

<?php start_block('breadcrumb'); ?>
<?php render_breadcrumb([
  ['label' => 'Dashboard', 'link' => admin_route('dashboard')],
  ['label' => 'Ajustes', 'link' => admin_route('settings/general')],
  ['label' => 'Identidad Visual']
]) ?>
<?php end_block(); ?>

<?php start_block("css") ?>
<link rel="stylesheet" href="<?= APP_URL . "/static/plugins/dropzone/dropimg.css" ?>">
<style>
  .brand-simulator {
    transition: all 0.3s ease;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  /* Clases personalizadas para simuladores (Safe for Dark Mode) */
  .sim-bg-light { background-color: #f8f9fa; }
  .sim-bg-dark { background-color: #212529; }

  .simulator-controls {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 5;
  }

  .bg-checkerboard {
    background-image: linear-gradient(45deg, #e9ecef 25%, transparent 25%), linear-gradient(-45deg, #e9ecef 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #e9ecef 75%), linear-gradient(-45deg, transparent 75%, #e9ecef 75%);
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
  }

  .favicon-preview-tab {
    background: rgba(0,0,0,0.05);
    border-radius: 8px 8px 0 0;
    padding: 8px 15px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
  }

  .social-preview-card {
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    max-width: 500px;
  }
  [data-bs-theme="dark"] .social-preview-card {
    background: #2b3035;
    border-color: rgba(255,255,255,0.1);
  }

  .social-preview-content {
    padding: 12px;
  }

  .social-preview-title {
    font-weight: 600;
    margin-bottom: 4px;
  }

  .social-preview-desc {
    font-size: 0.85rem;
    opacity: 0.7;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>
<?php end_block() ?>

<div class="row g-3">
  <!-- SECCIÓN DE LOGOS -->
  <div class="col-12">
    <div class="card">
      <div class="card-body p-4">
        <div class="d-flex align-items-center mb-4">
          <div class="p-2 rounded-3 me-3" style="background-color: #fcd; color: #f05;">
            <i class="fa-solid fa-palette fs-4"></i>
          </div>
          <div>
            <h5 class="card-title mb-0">Logotipos del Sitio</h5>
            <p class="text-muted small mb-0">Gestiona las versiones de tu identidad visual (Soportan Light/Dark Mode).</p>
          </div>
        </div>

        <div class="row g-4">
          <!-- Logo Oscuro -->
          <div class="col-md-6">
            <form method="post" enctype="multipart/form-data">
              <div class="p-3 border rounded-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="badge bg-primary-subtle text-primary">Logo Principal (Para fondo claro)</span>
                </div>
                
                <div id="sim-dark" class="brand-simulator rounded-3 mb-3 sim-bg-light">
                  <div class="simulator-controls">
                    <button type="button" class="btn btn-xs btn-outline-secondary" onclick="toggleSim('sim-dark', 'sim-bg-light', 'bg-checkerboard')">
                      <i class="fa-solid fa-border-all"></i>
                    </button>
                  </div>
                  <input type="file" name="st_darklogo" data-dropimg data-width="320" data-height="71"
                    data-default="<?= APP_URL ?>/storage/uploads/site/<?= $options->dark_logo ?>"
                    accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="text-muted small">
                    <i class="fa-solid fa-circle-info me-1"></i> 320x71px
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold text-uppercase small">
                    <i class="fa-solid fa-check me-1"></i> Actualizar
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- Logo Blanco -->
          <div class="col-md-6">
            <form method="post" enctype="multipart/form-data">
              <div class="p-3 border rounded-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="badge bg-secondary-subtle text-secondary">Logo Alternativo (Para fondo oscuro)</span>
                </div>

                <div id="sim-white" class="brand-simulator rounded-3 mb-3 sim-bg-dark">
                  <div class="simulator-controls">
                    <button type="button" class="btn btn-xs btn-outline-light" onclick="toggleSim('sim-white', 'sim-bg-dark', 'bg-checkerboard')">
                      <i class="fa-solid fa-border-all"></i>
                    </button>
                  </div>
                  <input type="file" name="st_whitelogo" data-dropimg data-width="320" data-height="71"
                    data-default="<?= APP_URL ?>/storage/uploads/site/<?= $options->white_logo ?>"
                    accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="text-muted small">
                    <i class="fa-solid fa-circle-info me-1"></i> 320x71px
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold text-uppercase small">
                    <i class="fa-solid fa-check me-1"></i> Actualizar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SECCIÓN DE FAVICON -->
  <div class="col-md-5">
    <div class="card h-100">
      <div class="card-body p-4">
        <h6 class="fw-bold text-uppercase small text-primary mb-4">Favicon & Browsers</h6>
        
        <form method="post" enctype="multipart/form-data">
          <!-- Simulador Navegador -->
          <div class="mb-4">
            <div class="favicon-preview-tab border border-bottom-0">
              <div id="fav-sim-icon" style="width: 16px; height: 16px;">
                <img src="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $st_favicon['favicon-16x16'] ?? 'favicon.ico' ?>" class="w-100 h-100 object-fit-contain">
              </div>
              <span class="small fw-medium"><?= $config->get('site_name') ?></span>
              <i class="fa-solid fa-xmark opacity-50" style="font-size: 10px;"></i>
            </div>
            <div class="pt-3 p-4 rounded-end rounded-bottom border text-center">
               <div style="max-width: 128px; margin: 0 auto;">
                  <input type="file" name="st_favicon" data-dropimg data-width="128" data-height="128"
                    data-default="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $st_favicon['favicon-32x32'] ?? 'favicon.ico' ?>"
                    accept=".png">
               </div>
            </div>
          </div>

          <div class="alert alert-info border-0 bg-info-subtle small mb-4">
            <i class="fa-solid fa-circle-info me-2"></i>
            Sube un <strong>PNG de 512x512px</strong>. El sistema generará automáticamente todos los formatos.
          </div>

          <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase small">
            <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generar Assets Visuales
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- SECCIÓN SOCIAL / OG -->
  <div class="col-md-7">
    <div class="card h-100">
      <div class="card-body p-4">
        <h6 class="fw-bold text-uppercase small text-primary mb-4">Redes Sociales (Open Graph)</h6>

        <form method="post" enctype="multipart/form-data">
          <div class="social-preview-card mb-4 mx-auto">
            <div style="aspect-ratio: 1200 / 630; background: rgba(0,0,0,0.03); border-bottom: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
              <input type="file" name="st_og_image" data-dropimg data-width="1200" data-height="630"
                data-default="<?= APP_URL ?>/storage/uploads/site/<?= $options->og_image ?>"
                accept=".jpg,.jpeg,.png,.webp">
            </div>
            <div class="social-preview-content">
              <div class="text-uppercase opacity-50" style="font-size: 10px;"><?= parse_url(APP_URL, PHP_URL_HOST) ?></div>
              <div class="social-preview-title"><?= $config->get('site_name') ?></div>
              <div class="social-preview-desc"><?= $config->get('site_description') ?></div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center">
             <span class="text-muted small"><i class="fa-solid fa-image me-1"></i> 1200x630px</span>
             <button type="submit" class="btn btn-outline-primary px-4 fw-bold text-uppercase small">Actualizar Imagen Social</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php start_block("js") ?>
<script src="<?= APP_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();

  function toggleSim(id, originalClass, newClass) {
    const el = document.getElementById(id);
    if (el.classList.contains(originalClass)) {
      el.classList.remove(originalClass);
      el.classList.add(newClass);
    } else {
      el.classList.remove(newClass);
      el.classList.add(originalClass);
    }
  }
</script>
<?php end_block() ?>