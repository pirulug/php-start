<link rel="stylesheet" href="<?= APP_URL . "/static/plugins/dropzone/dropimg.css" ?>">

<div class="card">
  <div class="card-body">

    <h6 class="fw-bold text-body-secondary text-uppercase small mb-3">Identidad de Marca</h6>
    <div class="row g-4 mb-5">

      <div class="col-md-6">
        <form method="post" enctype="multipart/form-data" class="h-100">
          <div class="border rounded p-3 bg-light h-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="fw-bold text-dark">Logo Principal (Oscuro)</label>
              <span class="badge bg-white text-dark border">Uso en fondos claros</span>
            </div>
            <div class="flex-grow-1 mb-3">
              <input type="file" id="st_darklogo" name="st_darklogo" data-dropimg data-width="300" data-height="71"
                data-default="<?= APP_URL . "/uploads/site/" . $optionsRaw['dark_logo'] ?>"
                accept=".jpg,.jpeg,.png,.gif,.webp" data-required>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-auto">
              <small class="text-muted">Recomendado: 300x71 px</small>
              <button class="btn btn-dark btn-sm px-3" type="submit">
                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Guardar
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-md-6">
        <form method="post" enctype="multipart/form-data" class="h-100">
          <div class="border rounded p-3 bg-dark h-100 d-flex flex-column" data-bs-theme="dark">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="fw-bold text-white">Logo Alternativo (Blanco)</label>
              <span class="badge bg-secondary text-white border border-secondary">Uso en fondos oscuros</span>
            </div>
            <div class="flex-grow-1 mb-3">
              <input type="file" id="st_whitelogo" name="st_whitelogo" data-dropimg data-width="300" data-height="71"
                data-default="<?= APP_URL . "/uploads/site/" . $optionsRaw['white_logo'] ?>"
                accept=".jpg,.jpeg,.png,.gif,.webp" data-required>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-auto">
              <small class="text-white-50">Recomendado: 300x71 px</small>
              <button class="btn btn-light btn-sm px-3" type="submit">
                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Guardar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <hr class="text-secondary opacity-25 my-4">

    <h6 class="fw-bold text-body-secondary text-uppercase small mb-3">Web & Social Media</h6>
    <div class="row g-4">

      <div class="col-md-4 col-lg-3">
        <form method="post" enctype="multipart/form-data" class="h-100">
          <div class="d-flex flex-column h-100">
            <label class="form-label fw-semibold">Favicon</label>
            <div class="mb-2">
              <input type="file" id="st_favicon" name="st_favicon" data-dropimg data-width="128" data-height="128"
                data-default="<?= APP_URL . "/uploads/site/favicons/" . $st_favicon['favicon.ico'] ?>"
                accept=".jpg,.jpeg,.png,.gif,.webp,.ico" data-required>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-auto gap-2">
              <small class="text-muted">128x128 px</small>
              <button class="btn btn-outline-primary btn-sm w-100" type="submit">Actualizar</button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-md-8 col-lg-9">
        <form method="post" enctype="multipart/form-data" class="h-100">
          <div class="d-flex flex-column h-100">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fw-semibold">Imagen Open Graph (Social Share)</label>
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Facebook / Twitter /
                WhatsApp</span>
            </div>
            <div class="mb-2 flex-grow-1">
              <input type="file" id="st_og_image" name="st_og_image" data-dropimg data-width="1200" data-height="630"
                data-default="<?= APP_URL ?>/uploads/site/<?= $optionsRaw['og_image'] ?>"
                accept=".jpg,.jpeg,.png,.gif,.webp" data-required>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto">
              <small class="text-muted"><i class="fa-solid fa-ruler-combined me-1"></i>Recomendado: 1200x630 px</small>
              <button class="btn btn-outline-primary btn-sm" type="submit">Actualizar Imagen</button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script src="<?= APP_URL . "/static/plugins/dropzone/dropimg.js" ?>"></script>
<script>
  DropImg.init();
</script>