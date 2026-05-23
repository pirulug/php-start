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
<?= static_libs_css("dropzone", "dropimg.css") ?>
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
  .sim-bg-light {
    background-color: #f8f9fa;
  }

  .sim-bg-dark {
    background-color: #212529;
  }

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
    background: rgba(0, 0, 0, 0.05);
    border-radius: 8px 8px 0 0;
    padding: 8px 15px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
  }

  .social-preview-card {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    max-width: 500px;
  }

  [data-bs-theme="dark"] .social-preview-card {
    background: #2b3035;
    border-color: rgba(255, 255, 255, 0.1);
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

  /* Preview Theme Styles */
  #logoPreviewContainer[data-preview-theme="light"] {
    background-color: #ffffff !important;
    color: #212529 !important;
  }
  #logoPreviewContainer[data-preview-theme="dark"] {
    background-color: #212529 !important;
    color: #f8f9fa !important;
  }
  #logoPreviewContainer[data-preview-theme="light"] .logo-dark {
    display: none !important;
  }
  #logoPreviewContainer[data-preview-theme="light"] .logo-light {
    display: block !important;
  }
  #logoPreviewContainer[data-preview-theme="dark"] .logo-light {
    display: none !important;
  }
  #logoPreviewContainer[data-preview-theme="dark"] .logo-dark {
    display: block !important;
  }
  #logoPreviewContainer[data-preview-theme="light"] .text-body {
    color: #212529 !important;
  }
  #logoPreviewContainer[data-preview-theme="dark"] .text-body {
    color: #f8f9fa !important;
  }
</style>
<?php end_block() ?>

<?php start_block("js") ?>
<?= static_libs_js("dropzone", "dropimg.js") ?>
<?= url_script_admin('settings', 'brand') ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('logoTypeSelect');
    const previewContainer = document.getElementById('logoPreviewContainer');
    const btnTogglePreviewTheme = document.getElementById('btnTogglePreviewTheme');
    
    // Controles de Icono
    const iconSettingsSection = document.getElementById('iconSettingsSection');
    const iconSource = document.getElementById('logoIconSource');
    const iconClassGroup = document.getElementById('iconClassGroup');
    const iconSvgGroup = document.getElementById('iconSvgGroup');
    const iconImageGroup = document.getElementById('iconImageGroup');
    
    const iconClassInput = document.getElementById('logoIconClass');
    const iconSvgInput = document.getElementById('logoIconSvg');
    const iconFileInput = document.getElementById('logoIconFile');
    
    const iconColorInput = document.getElementById('logoIconColor');
    const iconColorText = document.getElementById('logoIconColorText');
    
    if (select && previewContainer) {
      const siteName = <?= json_encode($config->siteName()) ?>;
      const logoDarkUrl = <?= json_encode(storage_uploads($config->logo()->dark, "site")) ?>;
      const logoLightUrl = <?= json_encode(storage_uploads($config->logo()->light, "site")) ?>;
      let uploadedIconUrl = <?= json_encode($config->get('logo_icon_file') ? storage_uploads($config->get('logo_icon_file'), "site") : '') ?>;
      
      const iconColorGroup = document.getElementById('iconColorGroup');
      
      const updateIconVisibility = () => {
        if (select.value === 'text') {
          iconSettingsSection.style.display = 'block';
          
          const source = iconSource.value;
          iconClassGroup.style.display = source === 'class' ? 'block' : 'none';
          iconColorGroup.style.display = source === 'class' ? 'block' : 'none';
          iconSvgGroup.style.display = source === 'svg_raw' ? 'block' : 'none';
          iconImageGroup.style.display = source === 'image' ? 'block' : 'none';
        } else {
          iconSettingsSection.style.display = 'none';
        }
      };

      if (iconFileInput) {
        iconFileInput.addEventListener('change', (e) => {
          const file = e.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
              uploadedIconUrl = event.target.result;
              renderPreview();
            };
            reader.readAsDataURL(file);
          } else {
            uploadedIconUrl = '';
            renderPreview();
          }
        });
      }

      if (iconColorInput && iconColorText) {
        iconColorInput.addEventListener('input', (e) => {
          iconColorText.value = e.target.value;
          renderPreview();
        });
        iconColorText.addEventListener('input', (e) => {
          if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
            iconColorInput.value = e.target.value;
            renderPreview();
          }
        });
      }

      const getIconHTML = () => {
        const source = iconSource.value;
        const color = iconColorInput ? iconColorInput.value : '';
        const colorStyle = color ? `style="color: ${color} !important;"` : '';
        
        if (source === 'none') {
          return '';
        } else if (source === 'svg_raw') {
          return iconSvgInput.value || `<i class="fa-solid fa-bolt"></i>`;
        } else if (source === 'image') {
          if (uploadedIconUrl) {
            return `<img src="${uploadedIconUrl}" alt="Icon" style="height: 24px; width: auto; object-fit: contain;">`;
          }
          return `<i class="fa-solid fa-image"></i>`;
        } else {
          const cls = iconClassInput.value || 'bi bi-lightning-charge-fill';
          return `<i class="${cls}" ${colorStyle}></i>`;
        }
      };
      
      const renderPreview = () => {
        const type = select.value;
        if (type === 'images') {
          previewContainer.innerHTML = `
            <div class="piru-nav-logo-images">
              <img class="piru-logo logo-light" src="${logoDarkUrl}" alt="${siteName}" style="height: 32px; width: auto; object-fit: contain;">
              <img class="piru-logo logo-dark" src="${logoLightUrl}" alt="${siteName}" style="height: 32px; width: auto; object-fit: contain;">
            </div>
          `;
        } else if (type === 'text') {
          previewContainer.innerHTML = `
            <div class="d-flex align-items-center gap-2">
              ${getIconHTML()}
              <span class="fw-bold fs-5 text-body" style="letter-spacing: -0.5px;">${siteName}</span>
            </div>
          `;
        }
      };

      // Control de Modo Claro/Oscuro de la vista previa
      if (btnTogglePreviewTheme) {
        btnTogglePreviewTheme.addEventListener('click', () => {
          const currentTheme = previewContainer.getAttribute('data-preview-theme') || 'light';
          const newTheme = currentTheme === 'light' ? 'dark' : 'light';
          previewContainer.setAttribute('data-preview-theme', newTheme);
          
          if (newTheme === 'dark') {
            btnTogglePreviewTheme.innerHTML = `<i class="fa-solid fa-sun me-1"></i> Modo Claro`;
          } else {
            btnTogglePreviewTheme.innerHTML = `<i class="fa-solid fa-moon me-1"></i> Modo Oscuro`;
          }
        });
      }
      
      select.addEventListener('change', () => {
        updateIconVisibility();
        renderPreview();
      });
      
      iconSource.addEventListener('change', () => {
        updateIconVisibility();
        renderPreview();
      });
      
      iconClassInput.addEventListener('input', renderPreview);
      iconSvgInput.addEventListener('input', renderPreview);
      
      updateIconVisibility();
      renderPreview();
    }
  });
</script>
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
            <p class="text-muted small mb-0">Gestiona las versiones de tu identidad
              visual (Soportan Light/Dark Mode).</p>
          </div>
        </div>

        <form method="post" class="mb-3" enctype="multipart/form-data">
          <div class="row align-items-end">
            <div class="col-md-8">
              <label class="form-label">Estilo de Logo en Navegación</label>
              <select name="st_logo_type" id="logoTypeSelect" class="form-select">
                <option value="images" <?= $config->get('logo_type', 'images') == 'images' ? 'selected' : '' ?>>Solo Imágenes (Light / Dark)</option>
                <option value="text" <?= $config->get('logo_type', 'images') == 'text' ? 'selected' : '' ?>>Solo Texto (Con Icono Personalizable)</option>
              </select>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary fw-bold text-uppercase small w-100">
                <i class="fa-solid fa-check me-1"></i>
                Guardar Estilo
              </button>
            </div>
          </div>

          <!-- Configuración del Icono (Solo visible si Tipo de Logo es "text") -->
          <div id="iconSettingsSection" class="mt-3 p-3 border rounded-3" style="display: none;">
            <div class="d-flex align-items-center mb-3">
              <i class="fa-solid fa-icons me-2 text-primary"></i>
              <span class="fw-bold small text-uppercase text-muted">Configuración de Icono (Estilo Solo Texto)</span>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Origen del Icono</label>
              <select name="st_logo_icon_source" id="logoIconSource" class="form-select">
                <option value="none" <?= $config->get('logo_icon_source', 'class') == 'none' ? 'selected' : '' ?>>Sin Icono (Solo Texto)</option>
                <option value="class" <?= $config->get('logo_icon_source', 'class') == 'class' ? 'selected' : '' ?>>Clase de Icono (FontAwesome / Bootstrap Icons)</option>
                <option value="svg_raw" <?= $config->get('logo_icon_source', 'class') == 'svg_raw' ? 'selected' : '' ?>>Código SVG Personalizado</option>
                <option value="image" <?= $config->get('logo_icon_source', 'class') == 'image' ? 'selected' : '' ?>>Subir Imagen / SVG</option>
              </select>
            </div>

            <!-- Configuración del Color del Icono -->
            <div id="iconColorGroup" class="mb-3">
              <label class="form-label">Color del Icono</label>
              <div class="d-flex align-items-center gap-2">
                <input type="color" name="st_logo_icon_color" id="logoIconColor" class="form-control form-control-color" value="<?= clear_html($config->get('logo_icon_color', '#ff0055')) ?>" title="Elige un color para el icono">
                <input type="text" id="logoIconColorText" class="form-control" value="<?= clear_html($config->get('logo_icon_color', '#ff0055')) ?>" placeholder="#ff0055" style="max-width: 120px;">
              </div>
              <div class="form-text">Si usas una clase de icono o SVG, este color se aplicará directamente.</div>
            </div>

            <!-- Opción: Clase de Icono -->
            <div id="iconClassGroup" class="mb-3">
              <label class="form-label">Clase del Icono <span class="text-danger">*</span></label>
              <input type="text" name="st_logo_icon_class" id="logoIconClass" class="form-control" value="<?= clear_html($config->get('logo_icon_class', 'bi bi-lightning-charge-fill')) ?>" placeholder="Ej. bi bi-lightning-charge-fill o fa-solid fa-bolt">
              <div class="form-text">Asegúrate de que la librería del icono esté disponible en el sitio.</div>
            </div>

            <!-- Opción: SVG Raw -->
            <div id="iconSvgGroup" class="mb-3" style="display: none;">
              <label class="form-label">Código SVG Raw <span class="text-danger">*</span></label>
              <textarea name="st_logo_icon_svg" id="logoIconSvg" class="form-control font-monospace" rows="4" placeholder="Ej: <svg ...>...</svg>"><?= clear_html($config->get('logo_icon_svg', '')) ?></textarea>
              <div class="form-text">Pega el código HTML &lt;svg&gt; directamente. El SVG heredará el tamaño e interacciones del tema.</div>
            </div>

            <!-- Opción: Subir Imagen/SVG -->
            <div id="iconImageGroup" class="mb-3" style="display: none;">
              <label class="form-label">Subir Icono (SVG, PNG, JPG, WebP) <span class="text-danger">*</span></label>
              <div style="max-width: 100px;">
                <input type="file" name="st_logo_icon_file" id="logoIconFile" data-dropimg data-width="100" data-height="100"
                  data-default="<?= $config->get('logo_icon_file') ? APP_URL . '/storage/uploads/site/' . $config->get('logo_icon_file') : '' ?>"
                  accept=".svg,.png,.jpg,.jpeg,.webp">
              </div>
            </div>
          </div>

          <div class="mt-3 p-3 border rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="small fw-bold text-uppercase text-muted">Vista Previa (Navegación Front-End)</div>
              <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase fw-bold" id="btnTogglePreviewTheme">
                <i class="fa-solid fa-moon me-1"></i> Modo Oscuro
              </button>
            </div>
            <div class="p-3 rounded border d-inline-block" id="logoPreviewContainer" data-preview-theme="light">
              <!-- JS Inyectará la vista previa aquí -->
            </div>
          </div>
        </form>

        <div class="row g-3">
          <!-- Logo Oscuro -->
          <div class="col-md-6">
            <form method="post" enctype="multipart/form-data">
              <div class="p-3 border rounded-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="badge bg-primary-subtle text-primary">Logo
                    Principal (Para fondo claro)</span>
                </div>

                <div id="sim-dark" class="brand-simulator rounded-3 mb-3 sim-bg-light">
                  <div class="simulator-controls">
                    <button type="button" class="btn btn-xs btn-outline-secondary"
                      onclick="toggleSim('sim-dark', 'sim-bg-light', 'bg-checkerboard')">
                      <i class="fa-solid fa-border-all"></i>
                    </button>
                  </div>
                  <input type="file" name="st_darklogo" data-dropimg data-width="320" data-height="71"
                    data-default="<?= APP_URL ?>/storage/uploads/site/<?= $config->dark_logo ?>"
                    accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="text-muted small">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    320x71px
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold text-uppercase small">
                    <i class="fa-solid fa-check me-1"></i>
                    Actualizar
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
                  <span class="badge bg-secondary-subtle text-secondary">Logo
                    Alternativo (Para fondo oscuro)</span>
                </div>

                <div id="sim-white" class="brand-simulator rounded-3 mb-3 sim-bg-dark">
                  <div class="simulator-controls">
                    <button type="button" class="btn btn-xs btn-outline-light"
                      onclick="toggleSim('sim-white', 'sim-bg-dark', 'bg-checkerboard')">
                      <i class="fa-solid fa-border-all"></i>
                    </button>
                  </div>
                  <input type="file" name="st_whitelogo" data-dropimg data-width="320" data-height="71"
                    data-default="<?= APP_URL ?>/storage/uploads/site/<?= $config->white_logo ?>"
                    accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="text-muted small">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    320x71px
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold text-uppercase small">
                    <i class="fa-solid fa-check me-1"></i>
                    Actualizar
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
          <div class="mb-3">
            <div class="favicon-preview-tab border border-bottom-0">
              <div id="fav-sim-icon" style="width: 16px; height: 16px;">
                <img
                  src="<?= APP_URL ?>/storage/uploads/site/favicons/<?= $st_favicon['favicon-16x16'] ?? 'favicon.ico' ?>"
                  class="w-100 h-100 object-fit-contain">
              </div>
              <span class="small fw-medium"><?= $config->site_name ?></span>
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

          <div class="alert alert-info bg-info-subtle small mb-3">
            <i class="fa-solid fa-circle-info me-2"></i>
            Sube un <strong>PNG de 512x512px</strong>. El sistema generará
            automáticamente todos los formatos.
          </div>

          <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase small">
            <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generar Assets
            Visuales
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- SECCIÓN SOCIAL / OG -->
  <div class="col-md-7">
    <div class="card h-100">
      <div class="card-body p-4">
        <h6 class="fw-bold text-uppercase small text-primary mb-3">Redes Sociales (Open Graph)
        </h6>

        <form method="post" enctype="multipart/form-data">
          <div class="social-preview-card mb-3 mx-auto">
            <div
              style="aspect-ratio: 1200 / 630; background: rgba(0,0,0,0.03); border-bottom: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
              <input type="file" name="st_og_image" data-dropimg data-width="1200" data-height="630"
                data-default="<?= APP_URL ?>/storage/uploads/site/<?= $config->og_image ?>"
                accept=".jpg,.jpeg,.png,.webp">
            </div>
            <div class="social-preview-content">
              <div class="text-uppercase opacity-50" style="font-size: 10px;">
                <?= parse_url(APP_URL, PHP_URL_HOST) ?>
              </div>
              <div class="social-preview-title"><?= $config->site_name ?>
              </div>
              <div class="social-preview-desc">
                <?= $config->site_description ?>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted small"><i class="fa-solid fa-image me-1"></i>
              1200x630px</span>
            <button type="submit" class="btn btn-outline-primary px-4 fw-bold text-uppercase small">Actualizar
              Imagen Social</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>